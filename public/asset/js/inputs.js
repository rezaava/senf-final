$(document).ready(function () {
  // مدیریت لیبل‌های شناور
  $(".input-field").on("focus", function () {
    $(this).siblings(".input-label").addClass("active");
  });

  $(".input-field").on("blur", function () {
    if (!$(this).val()) {
      $(this).siblings(".input-label").removeClass("active");
    }
  });

  // دکمه پاک‌کننده
  $(".clear-btn").on("click", function () {
    $(this).siblings(".input-field").val("").focus();
    $(this).siblings(".input-label").removeClass("active");
    $(this).hide();
    $(".dropdown-list").hide();
    $(".dropdown-item").removeClass("active");
  });

  // مدیریت نمایش/پنهان کردن دکمه پاک‌کننده
  $(".input-field").on("input", function () {
    const clearBtn = $(this).siblings(".clear-btn");
    if ($(this).val()) {
      clearBtn.show();
    } else {
      clearBtn.hide();
    }
  });

  // مدیریت لیست‌های کشویی
  $(".input-field").on("focus", function () {
    const dropdown = $(this).siblings(".dropdown-list");
    if (dropdown.length) {
      dropdown.show();
      // فعال کردن اولین گزینه
      const visibleItems = dropdown.find(".dropdown-item:visible");
      if (visibleItems.length > 0) {
        $(".dropdown-item").removeClass("active");
        visibleItems.first().addClass("active");
      }
    }
  });

  // فیلتر کردن لیست‌های کشویی
  $("#service-category, #service-city").on("input", function () {
    const value = $(this).val().toLowerCase();
    const dropdown = $(this).siblings(".dropdown-list");
    const items = dropdown.find(".dropdown-item");

    items.each(function () {
      const text = $(this).text().toLowerCase();
      if (text.includes(value)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    // فعال کردن اولین گزینه قابل مشاهده
    const visibleItems = dropdown.find(".dropdown-item:visible");
    if (visibleItems.length > 0) {
      $(".dropdown-item").removeClass("active");
      visibleItems.first().addClass("active");
    } else {
      $(".dropdown-item").removeClass("active");
    }
  });

  // انتخاب آیتم از لیست کشویی
  $(".dropdown-item").on("click", function () {
    const input = $(this).parent().siblings(".input-field");
    input.val($(this).text());
    $(this).parent().hide();
    input.siblings(".input-label").addClass("active");
    input.siblings(".clear-btn").show();
    $(".dropdown-item").removeClass("active");
    $(this).addClass('selected');
  });

  // مدیریت کیبورد برای لیست‌های کشویی
  $(".input-field").on("keydown", function (e) {
    const dropdown = $(this).siblings(".dropdown-list");
    if (!dropdown.is(":visible")) return;

    const items = dropdown.find(".dropdown-item:visible");
    if (items.length === 0) return;

    const activeItem = dropdown.find(".dropdown-item.active");
    let activeIndex = items.index(activeItem);

    switch (e.key) {
      case "ArrowDown":
        e.preventDefault();
        activeIndex = (activeIndex + 1) % items.length;
        items.removeClass("active");
        items.eq(activeIndex).addClass("active");
        break;

      case "ArrowUp":
        e.preventDefault();
        activeIndex = (activeIndex - 1 + items.length) % items.length;
        items.removeClass("active");
        items.eq(activeIndex).addClass("active");
        break;

      case "Enter":
        e.preventDefault();
        if (activeItem.length) {
          $(this).val(activeItem.text());
          dropdown.hide();
          $(this).siblings(".input-label").addClass("active");
          $(this).siblings(".clear-btn").show();
          items.removeClass("active");
        }
        break;

      case "Escape":
        dropdown.hide();
        items.removeClass("active");
        break;
    }
  });

  // پنهان کردن لیست کشویی هنگام کلیک خارج
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".input-group").length) {
      $(".dropdown-list").hide();
      $(".dropdown-item").removeClass("active");
    }
  });
});
