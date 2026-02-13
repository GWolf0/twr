@php
    $alerts = collect();

    /**
     * 1 Notifications array (highest priority)
     */
    if (session()->has('notifications') && is_array(session('notifications'))) {
        foreach (session('notifications') as $notification) {
            $alerts->push([
                'message' => $notification['message'] ?? null,
                'status' => $notification['status'] ?? null,
            ]);
        }
    }
    /**
     * 2 Single session message
     */ elseif (session()->has('message')) {
        $alerts->push([
            'message' => session('message'),
            'status' => session('status'),
        ]);
    }
    /**
     * 3 Query parameter message (?message=...)
     */ elseif (request()->has('message')) {
        $alerts->push([
            'message' => request()->query('message'),
            'status' => request()->query('status'),
        ]);
    }

    /**
     * Map HTTP status → severity
     */
    $mapSeverity = function ($status) {
        if (!$status) {
            return 'info';
        }

        $status = (int) $status;

        return match (true) {
            $status >= 200 && $status < 300 => 'success',
            $status >= 300 && $status < 400 => 'info',
            $status >= 400 && $status < 500 => 'warning',
            $status >= 500 => 'error',
            default => 'info',
        };
    };
@endphp

@if ($alerts->isNotEmpty())
    <div class="space-y-3 mb-4">
        @foreach ($alerts as $alert)
            @php
                $severity = $mapSeverity($alert['status'] ?? null);
            @endphp

            <x-ui.alert :message="$alert['message']" :severity="$severity" :autoclose="false" />
        @endforeach
    </div>
@endif
