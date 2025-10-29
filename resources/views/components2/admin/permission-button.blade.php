@props([
    'permission' => null,
    'permissions' => [],
    'action' => 'view',
    'class' => 'btn btn-primary',
    'icon' => null,
    'text' => 'Button',
    'href' => '#',
    'method' => 'GET',
    'confirm' => false,
    'confirmText' => 'Are you sure?'
])

@php
    $hasPermission = false;

    if ($permission) {
        $hasPermission = auth()->user()->can($permission);
    } elseif (!empty($permissions)) {
        $hasPermission = auth()->user()->canany($permissions);
    }
@endphp

@if($hasPermission)
    @if($method === 'GET')
        <a href="{{ $href }}" class="{{ $class }}">
            @if($icon)
                <i class="{{ $icon }}"></i>
            @endif
            {{ $text }}
        </a>
    @else
        <form action="{{ $href }}" method="POST" style="display: inline;"
              @if($confirm) onsubmit="return confirm('{{ $confirmText }}')" @endif>
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif
            <button type="submit" class="{{ $class }}">
                @if($icon)
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $text }}
            </button>
        </form>
    @endif
@endif
