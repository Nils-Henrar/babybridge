<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-header {{ $item['class'] ?? '' }}" style="color: white; font-size: 1.2em;">

    {{ is_string($item) ? $item : $item['header'] }}

</li>