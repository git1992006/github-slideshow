<?php
$pageTitle = 'Digital Second Brain | Personal Knowledge Vault';
require_once 'header.php';
?>
<section class="hero-section rounded-5 overflow-hidden position-relative mb-5">
    <div class="row g-0 align-items-center">
        <div class="col-lg-6 p-4 p-md-5">
            <span class="badge rounded-pill text-bg-light border mb-3 px-3 py-2">A modern productivity vault for focused thinkers</span>
            <h1 class="display-5 fw-bold mb-3">Turn scattered ideas into an organized personal knowledge system.</h1>
            <p class="lead text-secondary mb-4">Save notes, links, goals, reflections, and project knowledge in one polished dashboard. Connect related thoughts, pin what matters, and instantly resurface ideas when you need them.</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="register.php" class="btn btn-primary btn-lg rounded-pill px-4">Start Free</a>
                <a href="login.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Login</a>
            </div>
            <div class="row row-cols-2 row-cols-md-4 g-3 mt-4">
                <div class="col"><div class="mini-stat"><strong>Smart Search</strong><span>Find notes fast</span></div></div>
                <div class="col"><div class="mini-stat"><strong>Tag Graph</strong><span>Connect context</span></div></div>
                <div class="col"><div class="mini-stat"><strong>Archive Mode</strong><span>Declutter safely</span></div></div>
                <div class="col"><div class="mini-stat"><strong>Random Idea</strong><span>Rediscover insights</span></div></div>
            </div>
        </div>
        <div class="col-lg-6 p-4 p-md-5 bg-gradient-panel">
            <div class="app-preview card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-uppercase text-secondary small mb-1">Today's focus</p>
                            <h3 class="h4 mb-0">Knowledge dashboard</h3>
                        </div>
                        <span class="icon-pill"><i class="bi bi-stars"></i></span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6"><div class="metric-card"><span>Notes</span><strong>128</strong></div></div>
                        <div class="col-6"><div class="metric-card"><span>Favorites</span><strong>18</strong></div></div>
                    </div>
                    <div class="vault-note mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h4>Build a weekly review ritual</h4>
                                <p>Summarize wins, blockers, and strategic insights every Friday.</p>
                            </div>
                            <span class="badge text-bg-warning">High</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="badge text-bg-light border">productivity</span>
                            <span class="badge text-bg-light border">systems</span>
                            <span class="badge text-bg-light border">focus</span>
                        </div>
                    </div>
                    <div class="timeline-item"><i class="bi bi-link-45deg"></i> Linked to “Quarterly Planning” and “Goal Stack”.</div>
                    <div class="timeline-item"><i class="bi bi-lightning-charge"></i> Random idea prompt resurfaces dormant notes.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="mb-5">
    <div class="text-center mb-4">
        <span class="section-kicker">Feature highlights</span>
        <h2 class="fw-bold">Everything you need to build a personal second brain</h2>
        <p class="text-secondary">Designed to showcase authentication, relational data, search logic, and a presentation-ready UI.</p>
    </div>
    <div class="row g-4">
        <?php $features = [
            ['bi-shield-lock', 'Secure Accounts', 'Session-based login plus password hashing keep every user vault private.'],
            ['bi-journal-richtext', 'Deep Notes', 'Store rich descriptions, priorities, status, categories, and favorites in one workflow.'],
            ['bi-tags', 'Tags & Filters', 'Slice your vault using tags, categories, priorities, text search, and custom sorting.'],
            ['bi-diagram-3', 'Linked Thinking', 'Connect related notes so ideas evolve into structured knowledge networks.'],
            ['bi-archive', 'Archive System', 'Move inactive notes out of sight without losing valuable context.'],
            ['bi-shuffle', 'Random Idea', 'Pull one saved note at random whenever you need inspiration or review.'],
        ]; ?>
        <?php foreach ($features as [$icon, $title, $text]): ?>
            <div class="col-md-6 col-xl-4">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi <?= $icon ?>"></i></div>
                    <h3><?= h($title) ?></h3>
                    <p class="text-secondary mb-0"><?= h($text) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="how-it-works" class="cta-section rounded-5 p-4 p-lg-5 text-center">
    <span class="section-kicker">How it works</span>
    <h2 class="fw-bold mb-3">Capture. Organize. Connect. Review.</h2>
    <p class="text-secondary mx-auto mb-4" style="max-width: 760px;">Register an account, create your personal categories, capture notes with tags and priorities, then use search, favorites, linked notes, and random resurfacing to transform raw information into usable knowledge.</p>
    <a href="register.php" class="btn btn-primary btn-lg rounded-pill px-4">Launch Your Vault</a>
</section>
<?php require_once 'footer.php'; ?>
