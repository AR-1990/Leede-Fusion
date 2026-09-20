<div class="preloader" x-data="preloader()" x-show="visible" x-cloak
     x-transition:leave="preloader-leave"
     :style="leaving ? 'transform: translateY(-100%)' : ''">
    <div class="preloader-content">
        <h1 class="preloader-logo">
            @foreach(str_split('LEEDE') as $char)
                <span>{!! $char === ' ' ? '&nbsp;' : e($char) !!}</span>
            @endforeach
        </h1>
        <div class="preloader-counter">
            <span class="counter-number" x-text="count"></span>
            <span class="counter-symbol">%</span>
        </div>
    </div>
</div>
