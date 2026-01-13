<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class EmailSettingController extends Controller
{
    use AuthorizesAdminActions;

    /**
     * Show email settings form
     */
    public function index()
    {
        $this->authorizeAny(['settings.email']);

        $settings = EmailSetting::getSettings();
        $mailers = [
            'smtp' => 'SMTP',
            'sendmail' => 'Sendmail',
            'mailgun' => 'Mailgun',
            'ses' => 'Amazon SES',
            'postmark' => 'Postmark',
            'log' => 'Log (Testing)',
        ];

        $encryptions = [
            '' => 'None',
            'tls' => 'TLS',
            'ssl' => 'SSL',
        ];

        return view('admin.settings.email', compact('settings', 'mailers', 'encryptions'));
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        $this->authorizeAny(['settings.email']);

        $validated = $request->validate([
            'mailer' => 'required|in:smtp,sendmail,mailgun,ses,postmark,log',
            'host' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|in:,tls,ssl',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'reply_to_address' => 'nullable|email|max:255',
            'reply_to_name' => 'nullable|string|max:255',
        ]);

        try {
            $settings = EmailSetting::first() ?? new EmailSetting();

            $settings->mailer = $validated['mailer'];
            $settings->host = $validated['host'];
            $settings->port = $validated['port'];
            $settings->username = $validated['username'];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $settings->password = Crypt::encryptString($validated['password']);
            }

            $settings->encryption = $validated['encryption'];
            $settings->from_address = $validated['from_address'];
            $settings->from_name = $validated['from_name'];
            $settings->reply_to_address = $validated['reply_to_address'];
            $settings->reply_to_name = $validated['reply_to_name'];
            $settings->save();

            EmailSetting::clearCache();

            return redirect()->route('admin.settings.email')
                ->with('success', 'Pengaturan email berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Error saving email settings: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pengaturan email: ' . $e->getMessage());
        }
    }

    /**
     * Send test email
     */
    public function sendTest(Request $request)
    {
        $this->authorizeAny(['settings.email']);

        $request->validate([
            'test_email' => 'required|email|max:255',
        ]);

        try {
            $settings = EmailSetting::getSettings();

            if (!$settings) {
                return back()->with('error', 'Pengaturan email belum dikonfigurasi.');
            }

            // Apply settings to mail config
            $this->applyMailConfig($settings);

            // Send test email
            Mail::raw('Ini adalah email test dari ' . config('app.name') . '. Jika Anda menerima email ini, berarti konfigurasi SMTP sudah benar.', function ($message) use ($request, $settings) {
                $message->to($request->test_email)
                    ->subject('Test Email - ' . config('app.name'));

                if ($settings->reply_to_address) {
                    $message->replyTo($settings->reply_to_address, $settings->reply_to_name);
                }
            });

            return back()->with('success', 'Email test berhasil dikirim ke ' . $request->test_email);
        } catch (\Exception $e) {
            Log::error('Error sending test email: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email test: ' . $e->getMessage());
        }
    }

    /**
     * Apply mail configuration from database settings
     */
    private function applyMailConfig(EmailSetting $settings): void
    {
        Config::set('mail.default', $settings->mailer);
        Config::set('mail.mailers.smtp.host', $settings->host);
        Config::set('mail.mailers.smtp.port', $settings->port);
        Config::set('mail.mailers.smtp.username', $settings->username);
        Config::set('mail.mailers.smtp.password', $settings->getDecryptedPassword());
        Config::set('mail.mailers.smtp.encryption', $settings->encryption ?: null);
        Config::set('mail.from.address', $settings->from_address);
        Config::set('mail.from.name', $settings->from_name);
    }
}
