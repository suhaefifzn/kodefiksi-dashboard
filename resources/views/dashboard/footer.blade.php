<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        @php
            $initYear = '2024';
            $currentYear = date('Y');
            $copyrightYear = $initYear == $currentYear ? $currentYear : $initYear . ' - ' . $currentYear;
        @endphp
        <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center ms-auto">Copyright © {{ $copyrightYear }}. All rights reserved.</span>
    </div>
</footer>
