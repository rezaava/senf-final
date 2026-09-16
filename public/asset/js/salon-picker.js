/* =========================================================================
   SalonPicker — ویجت مشترک انتخاب سالن
   -------------------------------------------------------------------------
   در دو جا استفاده می‌شود:
     ۱) مرحله‌ی انتخاب سالن هنگام ورود  (auth/partials/salon-step.blade.php)
     ۲) مودال «تغییر سالن» در صفحه‌ی پروفایل (web/profile.blade.php)

   وابستگی‌ها:
     - jQuery
     - <template id="salonCardTemplate">  (partials/salon-card-template.blade.php)
     - asset/css/salon-select.css

   نمونه‌ی استفاده:
     const picker = SalonPicker({
         cards:   '#salonCards',
         search:  '#salonSearchBox',
         input:   '#salonSearchInput',
         empty:   '#salonEmptyState',
         onChange: function (salonId) { ... }
     });
     picker.render(salons);
     picker.getSelected();
   ========================================================================= */
window.SalonPicker = function (options) {
    'use strict';

    var opts = $.extend({
        cards: null,          // کانتینر کارت‌ها (اجباری)
        search: null,         // جعبه‌ی جست‌وجو (اختیاری)
        input: null,          // خود input جست‌وجو (اختیاری)
        empty: null,          // المان حالت خالی (اختیاری)
        emptyText: null,      // المان متن حالت خالی (اختیاری)
        searchThreshold: 4,   // از این تعداد سالن به بالا، جست‌وجو نمایش داده می‌شود
        autoSelectCurrent: true, // سالن فعلی کاربر از ابتدا انتخاب شده باشد
        onChange: null        // callback هنگام تغییر انتخاب
    }, options || {});

    var $cards = $(opts.cards);
    var $search = opts.search ? $(opts.search) : $();
    var $input = opts.input ? $(opts.input) : $();
    var $empty = opts.empty ? $(opts.empty) : $();
    var $emptyText = opts.emptyText ? $(opts.emptyText) : $();

    var selectedId = null;
    var salons = [];

    /** ساخت یک کارت از روی <template> */
    function buildCard(salon) {
        var tpl = document.getElementById('salonCardTemplate');
        var $card = $(tpl.content.firstElementChild.cloneNode(true));

        var name = salon.name || 'بدون نام';
        var address = [salon.city, salon.address].filter(Boolean).join('، ') || 'آدرس ثبت نشده است';

        $card.attr('data-salon-id', salon.id);
        // متن جست‌وجو از قبل آماده می‌شود تا فیلتر کردن سریع باشد
        $card.attr('data-search', (name + ' ' + address).toLowerCase());
        $card.attr('aria-label', name);

        // مقادیر با text() ست می‌شوند تا جلوی XSS گرفته شود
        $card.find('[data-salon-field="name"]').text(name);
        $card.find('[data-salon-field="address"]').text(address);
        $card.find('[data-salon-field="staff"]').text((salon.staff_count || 0) + ' آرایشگر');

        if (salon.image) {
            $card.find('[data-salon-field="avatar"]')
                .html($('<img>').attr({ src: salon.image, alt: name }));
        } else {
            $card.find('[data-salon-field="avatar"]').text(salon.initial || name.charAt(0));
        }

        if (salon.is_current) {
            $card.find('[data-salon-field="current"]').removeClass('is-hidden');
        }

        return $card;
    }

    /** انتخاب یک کارت */
    function select($card) {
        if (!$card || !$card.length) return;

        $cards.find('.salon-card').removeClass('selected').attr('aria-checked', 'false');
        $card.addClass('selected').attr('aria-checked', 'true');
        selectedId = $card.data('salon-id');

        if (typeof opts.onChange === 'function') {
            opts.onChange(selectedId, $card);
        }
    }

    /** رندر کردن لیست سالن‌ها */
    function render(list) {
        salons = list || [];
        selectedId = null;
        $cards.empty();

        salons.forEach(function (salon, index) {
            var $card = buildCard(salon);
            // ورود پلکانی کارت‌ها
            $card.css('animation-delay', Math.min(index, 8) * 60 + 'ms');
            $cards.append($card);
        });

        $search.toggleClass('is-hidden', salons.length < opts.searchThreshold);
        $input.val('');
        $empty.toggleClass('is-hidden', salons.length > 0);
        $emptyText.text('سالنی یافت نشد');

        if (opts.autoSelectCurrent) {
            var currentIndex = salons.findIndex(function (s) { return s.is_current; });
            if (currentIndex !== -1) {
                select($cards.find('.salon-card').eq(currentIndex));
            }
        }
    }

    /** فیلتر کردن بر اساس متن جست‌وجو */
    function filter(term) {
        term = (term || '').trim().toLowerCase();
        var visible = 0;

        $cards.find('.salon-card').each(function () {
            var match = !term || String($(this).data('search') || '').indexOf(term) !== -1;
            $(this).toggleClass('is-hidden', !match);
            if (match) visible++;
        });

        $empty.toggleClass('is-hidden', visible > 0);
        $emptyText.text('سالنی با این مشخصات پیدا نشد');
    }

    // --- رویدادها (delegation، چون کارت‌ها پویا ساخته می‌شوند) ---
    $cards.on('click', '.salon-card', function () {
        select($(this));
    });

    $cards.on('keydown', '.salon-card', function (e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
            e.preventDefault();
            select($(this));
        }
    });

    $input.on('input', function () {
        filter($(this).val());
    });

    return {
        render: render,
        getSelected: function () { return selectedId; },
        getSalons: function () { return salons; },
        clearSelection: function () {
            $cards.find('.salon-card').removeClass('selected').attr('aria-checked', 'false');
            selectedId = null;
        }
    };
};
