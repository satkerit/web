@props(['headers' => []])

<div class="overflow-hidden ring-1 ring-slate-200 sm:rounded-xl">
    <div class="table-responsive">
        <table class="min-w-full divide-y divide-slate-200">
            @if(count($headers) > 0)
                <thead class="bg-slate-50/75">
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="bg-white divide-y divide-slate-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
