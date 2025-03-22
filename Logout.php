<?php
session_start();     // بدء الجلسة
session_unset();     // إزالة جميع متغيرات الجلسة
session_destroy();   // إنهاء الجلسة
header("Location: index.html"); // إعادة التوجيه للصفحة الرئيسية أو صفحة تسجيل الدخول
exit();
?>
