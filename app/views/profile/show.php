<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>

<ul>
    <li>Email: <?= htmlspecialchars($user['email']) ?></li>
    <li>Role: <?= htmlspecialchars($user['role']) ?></li>
</ul>

<hr>

<nav>
    <?php if ($user['role'] === 'client'): ?>
        <a href="/TOnotDO/public/tasks/myRequests">My Requests</a>
    <?php elseif ($user['role'] === 'worker'): ?>
        <a href="/TOnotDO/public/tasks/myTasks">My Tasks</a>
    <?php elseif ($user['role'] === 'root'): ?>
        <a href="/TOnotDO/public/tasks/manage">Manage Tasks</a>
    <?php endif; ?>

    <br><br>
    <a href="/TOnotDO/public/profile/edit">Edit Profile</a>
    <a href="/TOnotDO/public/profile/changePassword">Change Password</a>
    <a href="/TOnotDO/public/profile/delete">Delete Account</a>
    
    <br><br>
    <a href="/TOnotDO/public/auth/logout">Logout</a>
</nav>

</body>
</html>
