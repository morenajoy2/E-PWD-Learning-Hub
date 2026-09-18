<?php
if (!isset($active)) {
    $active = "";
}
?>

<div class="topnav">
    <?php if ($role == "Admin"): ?>
        <a class="<?php echo ($active == 'dashboard') ? 'active' : ''; ?>" href="./admin/dashboard">Dashboard</a>
        <a class="<?php echo ($active == 'content') ? 'active' : ''; ?>" href="./admin/content/management">Content</a>
        <a class="<?php echo ($active == 'announcement') ? 'active' : ''; ?>" href="./admin/announcement/management">Announcement</a>
        <a class="<?php echo ($active == 'event') ? 'active' : ''; ?>" href="./admin/event/management">Event</a>
        <a class="<?php echo ($active == 'class-section') ? 'active' : ''; ?>" href="./admin/class-section/management">Class
            Section</a>
        <a class="<?php echo ($active == 'user') ? 'active' : ''; ?>" href="./admin/user/management">User</a>
        <a href="Login">Log Out</a>

    <?php elseif ($role == "Teacher"): ?>
        <a class="<?php echo ($active == 'dashboard') ? 'active' : ''; ?>" href="./teacher/dashboard">Dashboard</a>
        <a class="<?php echo ($active == 'content') ? 'active' : ''; ?>" href="./teacher/content/management">Content</a>
        <a class="<?php echo ($active == 'announcement') ? 'active' : ''; ?>"
            href="./teacher/announcement/management">Announcement</a>
        <a class="<?php echo ($active == 'event') ? 'active' : ''; ?>" href="./teacher/event/management">Event</a>
        <a class="<?php echo ($active == 'class-section') ? 'active' : ''; ?>" href="./teacher/class-section/management">Class
            Section</a>
        <a href="Login">Log Out</a>
    <?php elseif ($role == "Student"): ?>
        <a class="<?php echo ($active == 'dashboard') ? 'active' : '';
        ?>" href="./student/dashboard">Dashboard</a>
        <a class="<?php echo ($active == 'content') ? 'active' : '';
        ?>" href="./student/content/index">Content</a>
        <a class="<?php echo ($active == 'announcement') ? 'active' : '';
        ?>" href="./student/announcement/index">Announcement</a>
        <a class="<?php echo ($active == 'event') ? 'active' : '';
        ?>" href="./student/event/index">Event</a>
        <a href="Login">Log Out</a>
    <?php elseif ($role == "Parent"): ?>
        <a class="<?php echo ($active == 'dashboard') ? 'active' : '';
        ?>" href="./parent/dashboard">Dashboard</a>
        <a class="<?php echo ($active == 'announcement') ? 'active' : '';
        ?>" href="./parent/announcement/index">Announcement</a>
        <a class="<?php echo ($active == 'event') ? 'active' : '';
        ?>" href="./parent/event/index">Event</a>
        <a href="Login">Log Out</a>
    <?php endif; ?>
</div>