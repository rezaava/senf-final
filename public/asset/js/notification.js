// داده‌های نمونه نوتیفیکیشن‌ها
const notifications = [
  {
    id: 1,
    title: "پیام خوش‌آمدگویی",
    text: "به وبسایت ما خوش آمدید! امیدواریم از خدمات ما راضی باشید.",
    time: "۱۰ دقیقه پیش",
    read: false,
  },
  {
    id: 2,
    title: "به‌روزرسانی سیستم",
    text: "سیستم ما در تاریخ ۱۴۰۲/۰۵/۲۰ به‌روزرسانی خواهد شد. لطفاً اطلاعات خود را ذخیره کنید.",
    time: "۱ ساعت پیش",
    read: false,
  },
  {
    id: 3,
    title: "تراکنش موفق",
    text: "تراکنش شما با کد پیگیری ۱۲۳۴۵۶ با موفقیت انجام شد.",
    time: "۲ ساعت پیش",
    read: true,
  },
  {
    id: 4,
    title: "پیام پشتیبانی",
    text: "پیام شما توسط پشتیبانی دریافت و در حال بررسی می‌باشد.",
    time: "۵ ساعت پیش",
    read: false,
  },
  {
    id: 5,
    title: "تخفیف ویژه",
    text: "کد تخفیف ۲۰٪ برای خریدهای بالای ۲۰۰ هزار تومان: SPRING1402",
    time: "۱ روز پیش",
    read: true,
  },
  {
    id: 6,
    title: "تغییرات قوانین",
    text: "قوانین استفاده از خدمات به‌روزرسانی شد. لطفاً بخش قوانین را مطالعه کنید.",
    time: "۲ روز پیش",
    read: true,
  },
];

// تابع برای شمارش نوتیفیکیشن‌های خوانده نشده
function countUnreadNotifications() {
  return notifications.filter((notification) => !notification.read).length;
}

// تابع برای به‌روزرسانی بدج نوتیفیکیشن
function updateNotificationBadge() {
  const unreadCount = countUnreadNotifications();
  const badge = document.getElementById("notificationBadge");

  if (unreadCount > 0) {
    badge.textContent = unreadCount;
    badge.style.display = "flex";
  } else {
    badge.style.display = "none";
  }
}

// تابع برای رندر نوتیفیکیشن‌ها در مدال
function renderNotifications() {
  const notificationList = document.getElementById("notificationList");

  if (notifications.length === 0) {
    notificationList.innerHTML = `
                    <div class="empty-notifications">
                        <i class="bi bi-inbox"></i>
                        <h5>هیچ نوتیفیکیشنی وجود ندارد</h5>
                        <p>هیچ پیام جدیدی برای نمایش وجود ندارد.</p>
                    </div>
                `;
    return;
  }

  let notificationsHTML = "";

  notifications.forEach((notification) => {
    const readStatusClass = notification.read ? "" : "unread";
    const readStatusText = notification.read ? "خوانده شده" : "جدید";

    notificationsHTML += `
                    <div class="notification-item ${readStatusClass}" data-id="${notification.id}">
                        <div class="notification-title">
                            ${notification.title}
                            <span class="status-badge">${readStatusText}</span>
                        </div>
                        <div class="notification-text">${notification.text}</div>
                        <div class="notification-time">
                            <i class="bi bi-clock"></i>
                            ${notification.time}
                        </div>
                    </div>
                `;
  });

  notificationList.innerHTML = notificationsHTML;

  // اضافه کردن رویداد کلیک به هر آیتم نوتیفیکیشن
  document.querySelectorAll(".notification-item").forEach((item) => {
    item.addEventListener("click", function () {
      const id = parseInt(this.getAttribute("data-id"));
      markAsRead(id);
    });
  });
}

// تابع برای علامت‌گذاری یک نوتیفیکیشن به عنوان خوانده شده
function markAsRead(id) {
  const notification = notifications.find((n) => n.id === id);
  if (notification && !notification.read) {
    notification.read = true;
    updateNotificationBadge();
    renderNotifications();
  }
}

// تابع برای علامت‌گذاری همه نوتیفیکیشن‌ها به عنوان خوانده شده
function markAllAsRead() {
  notifications.forEach((notification) => {
    notification.read = true;
  });
  updateNotificationBadge();
  renderNotifications();
}

// وقتی مدال باز می‌شود، نوتیفیکیشن‌ها را رندر کن
const notificationModal = document.getElementById("notificationModal");
notificationModal.addEventListener("show.bs.modal", function () {
  renderNotifications();
});

// اضافه کردن رویداد کلیک به دکمه "خواندن همه"
document
  .getElementById("markAllReadBtn")
  .addEventListener("click", markAllAsRead);

// مقداردهی اولیه
document.addEventListener("DOMContentLoaded", function () {
  updateNotificationBadge();
});
