<div class="container-fluid jumbotron">
    <div class="col-sm-4">
        <div class="admin-stats"> ALL <span> <?=number_format($allFreeUsers + $allVipSilvers + $allAdmins + $allVipGolds + $allBannedUsers); ?> </span></div>
        <div class="admin-stats"> ADMIN <span> <?=number_format($allAdmins); ?> </span> </div>
        <div class="admin-stats"> SILVER <span> <?=number_format($allVipSilvers); ?> </span></div>
        <div class="admin-stats"> FREE <span> <?=number_format($allFreeUsers); ?> </span> </div>
        <div class="admin-stats">GOLD <span> <?=number_format($allVipGolds); ?> </span></div>
        <div class="admin-stats"> BANNED <span> <?=number_format($allBannedUsers); ?> </span> </div>
        <div class="admin-stats" <?=($allNotActivateUsers > 0) ? 'style="background-color:red;"' : null; ?>> INACTIVE <span> <?=number_format($allNotActivateUsers); ?> </span> </div>
    </div>
    <div id="users-charts" class="col-sm-8"></div>
    </div>
    
    
    <div class="container-fluid jumbotron">
    <div class="col-sm-4">
        <div class="admin-stats"> ALL <span> <?=number_format($allFreeUsers + $allVipSilvers + $allAdmins + $allVipGolds + $allBannedUsers); ?> </span></div>
        <div class="admin-stats"> ADMIN <span> <?=number_format($allAdmins); ?> </span> </div>
        <div class="admin-stats"> SILVER <span> <?=number_format($allVipSilvers); ?> </span></div>
        <div class="admin-stats"> FREE <span> <?=number_format($allFreeUsers); ?> </span> </div>
        <div class="admin-stats">GOLD <span> <?=number_format($allVipGolds); ?> </span></div>
        <div class="admin-stats"> BANNED <span> <?=number_format($allBannedUsers); ?> </span> </div>
        <div class="admin-stats"> INACTIVE <span> <?=number_format($allNotActivateUsers); ?> </span> </div>
    </div>
    <div id="messages-charts" class="col-sm-8"></div>
    </div>
    
    <div class="container-fluid jumbotron">
    <div class="col-sm-4">
        <div class="admin-stats"> ALL <span> <?=number_format($allFreeUsers + $allVipSilvers + $allAdmins + $allVipGolds + $allBannedUsers); ?> </span></div>
        <div class="admin-stats"> ADMIN <span> <?=number_format($allAdmins); ?> </span> </div>
        <div class="admin-stats"> SILVER <span> <?=number_format($allVipSilvers); ?> </span></div>
        <div class="admin-stats"> FREE <span> <?=number_format($allFreeUsers); ?> </span> </div>
        <div class="admin-stats">GOLD <span> <?=number_format($allVipGolds); ?> </span></div>
        <div class="admin-stats"> BANNED <span> <?=number_format($allBannedUsers); ?> </span> </div>
        <div class="admin-stats"> INACTIVE <span> <?=number_format($allNotActivateUsers); ?> </span> </div>
    </div>
    <div id="charts" class="col-sm-8"></div>
    </div>
    
    