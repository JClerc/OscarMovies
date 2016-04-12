
<nav class="nav">

    <div class="top-nav">
        <div class="group">
            <a href="/" class="entry entry-large">
                <span class="tooltip">Go back home</span>
                <img src="/assets/img/ghost/main.svg" alt="" class="img-responsive">
            </a>
        </div>

        <div class="group">
            <a href="/chat/" class="entry entry-link <?= $this->request->getResource() === 'chat' ? 'active' : '' ?>">
                <span class="tooltip">Chat with Oscar</span>
                <img src="/assets/img/icons/chat.svg" alt="" class="img-responsive">
            </a>
            <a href="/trending/" class="entry entry-link <?= $this->request->getResource() === 'trending' ? 'active' : '' ?>">
                <span class="tooltip">What's hot</span>
                <img src="/assets/img/icons/trending.svg" alt="" class="img-responsive entry-fix-height">
            </a>
            <a href="/stats/" class="entry entry-link <?= $this->request->getResource() === 'stats' ? 'active' : '' ?>">
                <span class="tooltip">Some statistics</span>
                <img src="/assets/img/icons/stats.svg" alt="" class="img-responsive">
            </a>
            <a href="/history/" class="entry entry-link <?= $this->request->getResource() === 'history' ? 'active' : '' ?>">
                <span class="tooltip">Recommandations history</span>
                <img src="/assets/img/icons/history.svg" alt="" class="img-responsive">
            </a>
        </div>
    </div>

    <div class="bottom-nav">
        <div class="group">
            <?php if ($this->auth->isLogged()): ?>
                <div class="entry entry-large">
                    <img src="<?= $this->auth->getUser()->getPicture() ?>" alt="" class="img-responsive img-round">
                </div>
                <a href="<?= $this->auth->getLogoutUrl() ?>" class="entry entry-link">
                    <span class="tooltip">Logout :(</span>
                    <img src="/assets/img/icons/logout.svg" alt="" class="img-responsive">
                </a>
            <?php else: ?>
                <div class="entry entry-large">
                    <img src="/assets/img/user/connect-facebook.png" alt="" class="img-responsive img-round">
                </div>
                <a href="<?= $this->auth->getLoginUrl() ?>" class="entry entry-link">
                    <span class="tooltip">Login using Facebook</span>
                    <img src="/assets/img/icons/login.svg" alt="" class="img-responsive">
                </a>
            <?php endif; ?>
        </div>        
    </div>

</nav>
