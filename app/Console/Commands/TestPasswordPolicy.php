<?php

namespace App\Console\Commands;

use App\Rules\StrongPassword;
use Illuminate\Console\Command;

class TestPasswordPolicy extends Command
{
    protected $signature = 'test:password-policy';
    protected $description = 'Test password policy validation';

    public function handle(): int
    {
        $this->info('🔐 Testing Password Policy...');
        $this->newLine();

        $testPasswords = [
            ['password' => 'password123', 'should_fail' => true, 'reason' => 'Common password'],
            ['password' => '12345678', 'should_fail' => true, 'reason' => 'Too short & no special chars'],
            ['password' => 'abcdefgh', 'should_fail' => true, 'reason' => 'No numbers/special chars'],
            ['password' => 'Password1', 'should_fail' => true, 'reason' => 'No special chars'],
            ['password' => 'Password123', 'should_fail' => true, 'reason' => 'Sequential chars'],
            ['password' => 'Passworddddd1!', 'should_fail' => true, 'reason' => 'Repeated chars'],
            ['password' => 'MyS3cur3P@ssw0rd!', 'should_fail' => false, 'reason' => 'Valid password'],
            ['password' => 'C0mpl3x!P@ssw0rd', 'should_fail' => false, 'reason' => 'Valid password'],
            ['password' => 'Str0ng#P@ss2024', 'should_fail' => false, 'reason' => 'Valid password'],
        ];

        $passed = 0;
        $failed = 0;

        foreach ($testPasswords as $test) {
            $password = $test['password'];
            $shouldFail = $test['should_fail'];
            $reason = $test['reason'];

            $rule = new StrongPassword();
            $errors = [];
            
            $rule->validate('password', $password, function($message) use (&$errors) {
                $errors[] = $message;
            });

            $hasFailed = !empty($errors);

            if ($shouldFail === $hasFailed) {
                $this->line("✅ <fg=green>PASS</> - '{$password}' - {$reason}");
                if ($hasFailed) {
                    $this->line("   └─ Error: " . $errors[0]);
                }
                $passed++;
            } else {
                $this->line("❌ <fg=red>FAIL</> - '{$password}' - {$reason}");
                if ($hasFailed) {
                    $this->line("   └─ Unexpected error: " . $errors[0]);
                } else {
                    $this->line("   └─ Should have failed but passed");
                }
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Results: {$passed} passed, {$failed} failed");
        
        if ($failed === 0) {
            $this->info('🎉 All tests passed! Password policy is working correctly.');
            return Command::SUCCESS;
        } else {
            $this->error('⚠️  Some tests failed. Please review the password policy.');
            return Command::FAILURE;
        }
    }
}
