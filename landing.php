<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>EduTrack Pro — Learning Tracker for Intellectually Disabled Children</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="/edutrack/css/style.css" rel="stylesheet">
<style>
/* ── Extra landing styles ── */
body{background:#fff}

/* HERO gradient mesh */
.hero-section{
  background:#fff;position:relative;overflow:hidden;
  padding-top:68px;
}
.hero-gradient{
  position:absolute;inset:0;pointer-events:none;z-index:0;
  background:
    radial-gradient(ellipse 70% 60% at 80% 10%, rgba(26,86,219,.07) 0%, transparent 70%),
    radial-gradient(ellipse 50% 50% at 10% 80%, rgba(8,145,178,.05) 0%, transparent 60%);
}
.hero-grid{
  position:absolute;inset:0;pointer-events:none;z-index:0;
  background-image:
    linear-gradient(rgba(13,31,60,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(13,31,60,.04) 1px, transparent 1px);
  background-size:44px 44px;
}

/* Typewriter */
#typed-text{color:var(--accent)}

/* Section divider */
.wave-divider{
  height:60px;overflow:hidden;position:relative;
}
.wave-divider svg{position:absolute;bottom:0;width:100%;height:100%}

/* Techy number cards */
.tech-num-card{
  background:var(--white);border:1px solid var(--border);
  border-radius:var(--r2);padding:24px 20px;text-align:center;
  box-shadow:var(--sh2);transition:all var(--t);position:relative;overflow:hidden;
}
.tech-num-card:hover{transform:translateY(-4px);box-shadow:var(--sh3)}
.tech-num-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--accent),var(--teal));
}
.tech-num-big{
  font-family:'Poppins',sans-serif;font-size:42px;font-weight:900;
  color:var(--navy);line-height:1;
}
.tech-num-lbl{font-size:12px;color:var(--muted);margin-top:6px;font-weight:500}

/* ── Animated orbit around hero visual ── */
.orbit-wrap{position:relative;display:inline-block}
.orbit-ring{
  position:absolute;inset:-18px;border-radius:50%;
  border:1px dashed rgba(26,86,219,.2);
  animation:orbit-spin 20s linear infinite;
}
.orbit-ring2{
  position:absolute;inset:-36px;border-radius:50%;
  border:1px dashed rgba(8,145,178,.15);
  animation:orbit-spin 30s linear infinite reverse;
}
@keyframes orbit-spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

/* ── Testimonial ── */
.testi-card{
  background:var(--white);border:1px solid var(--border);
  border-radius:var(--r2);padding:24px;box-shadow:var(--sh1);
  transition:all var(--t);
}
.testi-card:hover{box-shadow:var(--sh3);transform:translateY(-3px)}
.testi-quote{font-size:15px;color:var(--text2);line-height:1.8;margin-bottom:16px;font-style:italic}
.testi-av{
  width:38px;height:38px;border-radius:50%;background:var(--accent-s);
  display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:16px;
}
.testi-name{font-family:'Poppins',sans-serif;font-weight:700;font-size:13.5px;color:var(--navy)}
.testi-role{font-size:11.5px;color:var(--muted)}

/* ── Scroll indicator ── */
.scroll-ind{
  display:flex;flex-direction:column;align-items:center;gap:6px;
  margin-top:30px;color:var(--muted);font-size:12px;font-weight:500;
  animation:fadeUp .8s ease .8s both;
}
.scroll-ind-dot{
  width:24px;height:38px;border:2px solid var(--border2);border-radius:12px;
  display:flex;align-items:flex-start;justify-content:center;padding-top:5px;
}
.scroll-ind-dot::before{
  content:'';width:4px;height:8px;background:var(--accent);border-radius:2px;
  animation:scroll-bounce 2s ease-in-out infinite;
}
@keyframes scroll-bounce{0%,100%{transform:translateY(0);opacity:1}50%{transform:translateY(12px);opacity:.3}}

/* Team avatar gradient backgrounds */
.av-grad-1{background:linear-gradient(135deg,#1a56db,#0891b2)}
.av-grad-2{background:linear-gradient(135deg,#7c3aed,#1a56db)}
.av-grad-3{background:linear-gradient(135deg,#059669,#0891b2)}
.av-grad-4{background:linear-gradient(135deg,#d97706,#dc2626)}

/* Highlight badge pulse */
.hl-badge{
  display:inline-flex;align-items:center;gap:6px;
  background:rgba(5,150,105,.1);color:var(--green);
  border:1px solid rgba(5,150,105,.25);
  border-radius:20px;padding:5px 14px;
  font-size:12px;font-weight:700;
  animation:pulse-ring 2.5s infinite;
}

/* Feature grid hover glow */
.feat-card:hover::after{
  content:'';position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 0%,rgba(26,86,219,.04) 0%,transparent 70%);
  border-radius:var(--r2);pointer-events:none;z-index:0;
}
</style>
</head>

<body class="lp-root">

<!-- ════════════════ NAVBAR ════════════════ -->
<nav class="lp-nav" id="lpNav">
  <div class="lp-nav-inner">
    <a href="/edutrack/landing.php" class="lp-brand">
      <div class="lp-brand-box"><i class="fa-solid fa-brain"></i></div>
      <div class="lp-brand-name">EduTrack<span>Pro</span></div>
    </a>
    <div class="lp-nav-links">
      <a href="#features"    class="lp-nav-link">Features</a>
      <a href="#how"         class="lp-nav-link">How It Works</a>
      <a href="#purpose"     class="lp-nav-link">Mission</a>
      <a href="#team"        class="lp-nav-link">Team</a>
      <div style="width:1px;height:22px;background:var(--border);margin:0 8px"></div>
      <a href="/edutrack/login.php"  class="lp-btn-out">Sign In</a>
      <a href="/edutrack/signup.php" class="lp-btn-solid">Get Started <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>
  </div>
</nav>

<!-- ════════════════ HERO ════════════════ -->
<section class="hero-section">
  <div class="hero-gradient"></div>
  <div class="hero-grid"></div>

  <div class="lp-hero" style="position:relative;z-index:1">
    <!-- LEFT -->
    <div>
      <div class="hl-badge fu d1">
        <i class="fa-solid fa-circle" style="font-size:7px"></i> Built for Inclusive Education
      </div>

      <h1 class="hero-h1 fu d2">
        Every Child<br>Deserves to<br><span id="typed-text">Shine &amp; Grow</span>
      </h1>

      <p class="hero-desc fu d3">
        EduTrack Pro is a purpose-built learning management system designed for
        <strong>children with intellectual disabilities</strong> — empowering teachers and
        parents to track progress, celebrate every milestone, and build brighter futures.
      </p>

      <div class="hero-cta fu d4">
        <a href="/edutrack/signup.php" class="hero-cta-p">
          <i class="fa-solid fa-rocket"></i> Start Tracking Free
        </a>
        <a href="#how" class="hero-cta-s">
          <i class="fa-solid fa-play"></i> See How It Works
        </a>
      </div>

      <div class="hero-stats fu d5">
        <div>
          <div class="hs-num" data-count="500" data-suffix="+">500+</div>
          <div class="hs-lbl">Children Supported</div>
        </div>
        <div style="width:1px;background:var(--border)"></div>
        <div>
          <div class="hs-num" data-count="12" data-suffix="K+">12K+</div>
          <div class="hs-lbl">Sessions Logged</div>
        </div>
        <div style="width:1px;background:var(--border)"></div>
        <div>
          <div class="hs-num" data-count="98" data-suffix="%">98%</div>
          <div class="hs-lbl">Teacher Satisfaction</div>
        </div>
      </div>

      <div class="scroll-ind">
        <div class="scroll-ind-dot"></div>
        <span>Scroll to explore</span>
      </div>
    </div>

    <!-- RIGHT — MOCKUP -->
    <div class="hero-float-wrap fu d3">
      <!-- Floating badges -->
      <div class="float-badge fb1">
        <div class="fb-icon" style="background:var(--green-s);color:var(--green)"><i class="fa-solid fa-trophy"></i></div>
        <div>
          <div style="font-size:11px;color:var(--muted);font-weight:500">Best Score Today</div>
          <div style="color:var(--green)">92% — Arjun S.</div>
        </div>
      </div>
      <div class="float-badge fb2">
        <div class="fb-icon" style="background:var(--accent-s);color:var(--accent)"><i class="fa-solid fa-brain"></i></div>
        <div>
          <div style="font-size:11px;color:var(--muted);font-weight:500">AI Weak Area Alert</div>
          <div style="color:var(--accent)">Counting — 38%</div>
        </div>
      </div>

      <div class="hero-mockup">
        <div style="display:flex;align-items:center;margin-bottom:18px">
          <div class="hm-dots">
            <div class="hm-dot" style="background:#ef4444"></div>
            <div class="hm-dot" style="background:#f59e0b"></div>
            <div class="hm-dot" style="background:#22c55e"></div>
          </div>
          <div class="hm-bar-label">Student Dashboard · Live</div>
        </div>

        <!-- Student cards -->
        <div class="hm-card">
          <div class="hm-ico" style="background:rgba(34,197,94,.18);color:#4ade80"><i class="fa-solid fa-child-reaching"></i></div>
          <div style="flex:1">
            <div class="hm-ttl">Priya Mehta</div>
            <div class="hm-sub">Class 3 · Phonics · Session #14</div>
          </div>
          <div class="hm-pill" style="background:rgba(34,197,94,.15);color:#4ade80">87%</div>
        </div>

        <div class="hm-card">
          <div class="hm-ico" style="background:rgba(251,191,36,.18);color:#fbbf24"><i class="fa-solid fa-book-open"></i></div>
          <div style="flex:1">
            <div class="hm-ttl">Rohan Sharma</div>
            <div class="hm-sub">Class 4 · Colours · 18/20 correct</div>
          </div>
          <div class="hm-pill" style="background:rgba(251,191,36,.15);color:#fbbf24">52%</div>
        </div>

        <div class="hm-card">
          <div class="hm-ico" style="background:rgba(248,113,113,.18);color:#f87171"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div style="flex:1">
            <div class="hm-ttl">Weak Area Flagged</div>
            <div class="hm-sub">Number Recognition — needs review</div>
          </div>
          <div class="hm-pill" style="background:rgba(248,113,113,.15);color:#f87171">38%</div>
        </div>

        <!-- Mini chart -->
        <div class="hm-chart">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <div class="hm-chart-label">Weekly Progress</div>
            <div style="font-size:10px;color:rgba(255,255,255,.4)">↑ 12% this week</div>
          </div>
          <div class="hm-bars">
            <?php foreach([42,58,50,68,62,78,87] as $i=>$h): ?>
            <div class="hm-bar" style="height:<?= $h ?>%;animation:slideIn .4s ease <?= $i*.07 ?>s both"></div>
            <?php endforeach; ?>
          </div>
          <div class="hm-bar-days">
            <?php foreach(['M','T','W','T','F','S','S'] as $d): ?>
            <div class="hm-bar-d"><?= $d ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════ TRUST STRIP ════════════════ -->
<div class="lp-strip">
  <div class="lp-strip-inner">
    <?php foreach([
      ['fa-solid fa-shield-halved','100% Secure Data'],
      ['fa-solid fa-universal-access','Inclusive by Design'],
      ['fa-solid fa-chart-line','Evidence-Based Tracking'],
      ['fa-solid fa-users','Built with Educators'],
      ['fa-solid fa-certificate','XAMPP Ready'],
      ['fa-solid fa-heart','Made with Purpose'],
    ] as [$ic,$lbl]): ?>
    <div class="strip-item"><i class="<?= $ic ?>"></i> <?= $lbl ?></div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ════════════════ STATS ════════════════ -->
<section class="lp-section center">
  <div class="sec-eyebrow reveal"><i class="fa-solid fa-star"></i> By The Numbers</div>
  <h2 class="sec-h reveal">Making a Real Difference</h2>
  <p class="sec-sub reveal">Trusted by special educators and parents across schools and learning centres.</p>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px">
    <?php foreach([
      ['500+','Children Tracked','fa-solid fa-children','var(--accent)'],
      ['12K+','Sessions Logged','fa-solid fa-pen-to-square','var(--teal)'],
      ['98%','Teacher Satisfaction','fa-solid fa-star','var(--amber)'],
      ['3x','Faster Reports','fa-solid fa-bolt','var(--green)'],
    ] as [$n,$l,$ic,$c]): ?>
    <div class="tech-num-card reveal">
      <div style="font-size:28px;color:<?= $c ?>;margin-bottom:10px"><i class="<?= $ic ?>"></i></div>
      <div class="tech-num-big"><?= $n ?></div>
      <div class="tech-num-lbl"><?= $l ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ════════════════ FEATURES ════════════════ -->
<div class="lp-section-alt" id="features">
<div class="lp-section">
  <div class="center">
    <div class="sec-eyebrow reveal"><i class="fa-solid fa-layer-group"></i> Features</div>
    <h2 class="sec-h reveal">Everything You Need in One Place</h2>
    <p class="sec-sub reveal">Powerful tools crafted with compassion — for special educators, parents, and every unique learner.</p>
  </div>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:22px">
    <?php
    $feats = [
      ['fa-solid fa-user-graduate','Student Profiles','var(--accent)','var(--accent-s)','Complete profiles with guardian details, class, age, and session history for every learner.'],
      ['fa-solid fa-pen-to-square','Session Logging','var(--teal)','var(--teal-s)','Log every session in seconds — tasks attempted, correct answers, score, and time taken.'],
      ['fa-solid fa-chart-line','Performance Analysis','var(--purple)','var(--purple-s)','Track accuracy, identify weak areas, flag strong topics, and visualise improvement trends.'],
      ['fa-solid fa-file-invoice','Report Generation','var(--amber)','var(--amber-s)','One-click Teacher & Parent reports — printable as PDF, simple language, visually clear.'],
      ['fa-solid fa-book-open','Topic Management','var(--accent)','var(--accent-s)','Organise topics by subject area, link to sessions, and see topic-wise accuracy at a glance.'],
      ['fa-solid fa-gauge-high','Live Dashboard','var(--green)','var(--green-s)','Beautiful, data-rich dashboard with score trends, radar charts, top performers, and alerts.'],
      ['fa-solid fa-bell','Weak Area Alerts','var(--red)','var(--red-s)','Automatic detection of topics scoring below 50% — so no learning gap goes unnoticed.'],
      ['fa-solid fa-trophy','Progress Milestones','var(--amber)','var(--amber-s)','Celebrate growth with every session — because every correct answer is a milestone worth tracking.'],
      ['fa-solid fa-lock','Secure Multi-User','var(--teal)','var(--teal-s)','Role-based login for teachers. Each teacher sees only their own students and sessions.'],
    ];
    foreach($feats as $i=>[$ic,$h,$c,$bg,$p]): ?>
    <div class="feat-card reveal" style="--fc-top:<?= $c ?>;--fc-bg:<?= $bg ?>;--fc-c:<?= $c ?>;position:relative">
      <div class="feat-ico"><i class="<?= $ic ?>"></i></div>
      <div class="feat-h"><?= $h ?></div>
      <div class="feat-p"><?= $p ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</div>

<!-- ════════════════ HOW IT WORKS ════════════════ -->
<section class="lp-section center" id="how">
  <div class="sec-eyebrow reveal"><i class="fa-solid fa-circle-info"></i> How It Works</div>
  <h2 class="sec-h reveal">Simple. Purposeful. Effective.</h2>
  <p class="sec-sub reveal">Get set up in minutes — no technical expertise required.</p>
  <div class="steps-row">
    <?php foreach([
      ['1','fa-solid fa-user-plus','Sign Up Free','Create your teacher account in under a minute — no credit card needed.'],
      ['2','fa-solid fa-children','Add Students','Enter each child\'s profile with class and guardian information.'],
      ['3','fa-solid fa-pen-to-square','Log Sessions','Record tasks, scores, answers, and teacher observations for each session.'],
      ['4','fa-solid fa-chart-line','Track Growth','Analyse performance, generate reports, and celebrate every milestone.'],
    ] as [$n,$ic,$t,$d]): ?>
    <div class="step-box reveal">
      <div class="step-num"><?= $n ?></div>
      <div style="font-size:26px;color:var(--accent);margin-bottom:12px"><i class="<?= $ic ?>"></i></div>
      <div class="step-h"><?= $t ?></div>
      <div class="step-p"><?= $d ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ════════════════ PURPOSE BAND ════════════════ -->
<section class="lp-purpose" id="purpose">
  <div class="lp-purpose-inner">
    <div>
      <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:5px 14px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;display:inline-flex;align-items:center;gap:7px;margin-bottom:18px">
        <i class="fa-solid fa-hands-holding-child"></i> Our Mission
      </div>
      <h2 class="purpose-h">Built for Children Who Learn Differently</h2>
      <p class="purpose-p">
        Children with intellectual disabilities often learn at their own pace — in their own beautiful, unique way.
        EduTrack Pro was created to honour that uniqueness, giving educators and parents the tools to
        <em>truly see, support, and celebrate</em> every child's learning journey.
      </p>
      <p class="purpose-p">
        No child should be reduced to a number. Our system captures the full story — from time taken to
        encouragement notes — building a compassionate record of growth that goes beyond test scores.
      </p>
      <div class="purpose-chips">
        <?php foreach(['Personalised Tracking','Parent-Friendly Reports','Evidence-Based Insights','Zero Tech Barrier','Inclusive by Design'] as $c): ?>
        <span class="p-chip"><i class="fa-solid fa-check" style="color:#4ade80"></i> <?= $c ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="purpose-grid reveal">
      <?php foreach([
        ['500+','Children Tracked'],
        ['12K+','Sessions Logged'],
        ['3×','Faster Reports'],
        ['98%','Teacher Satisfaction'],
      ] as [$n,$l]): ?>
      <div class="p-card">
        <div class="p-card-n"><?= $n ?></div>
        <div class="p-card-l"><?= $l ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════ TESTIMONIALS ════════════════ -->
<section class="lp-section" id="testi">
  <div class="center">
    <div class="sec-eyebrow reveal"><i class="fa-solid fa-quote-left"></i> Testimonials</div>
    <h2 class="sec-h reveal">What Educators Are Saying</h2>
    <p class="sec-sub reveal">Real words from teachers and parents who use EduTrack Pro every day.</p>
  </div>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:22px">
    <?php foreach([
      ['"Finally a tracker that sees my students as individuals, not just test scores. The weak area alerts have been a game-changer."','Ms. Ranjita Iyer','Special Educator, Mumbai'],
      ['"The parent reports are so simple and warm. Parents actually understand and appreciate them — no jargon, just progress."','Mr. Deepak Rao','Resource Teacher, Pune'],
      ['"It helped me realise Kabir truly loves phonics. Now I structure his sessions around his strengths and he\'s thriving!"','Mrs. Sunita Pillai','Inclusion Teacher, Bangalore'],
    ] as [$q,$n,$r]): ?>
    <div class="testi-card reveal">
      <div style="color:var(--accent);font-size:22px;margin-bottom:12px"><i class="fa-solid fa-quote-left"></i></div>
      <div class="testi-quote"><?= $q ?></div>
      <div style="display:flex;align-items:center;gap:10px;border-top:1px solid var(--border);padding-top:14px">
        <div class="testi-av"><i class="fa-solid fa-chalkboard-user"></i></div>
        <div>
          <div class="testi-name"><?= $n ?></div>
          <div class="testi-role"><?= $r ?></div>
        </div>
        <div style="margin-left:auto;color:#f59e0b">★★★★★</div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ════════════════ TEAM ════════════════ -->
<div class="lp-section-alt" id="team">
<div class="lp-section">
  <div class="center">
    <div class="sec-eyebrow reveal"><i class="fa-solid fa-people-group"></i> The Team</div>
    <h2 class="sec-h reveal">Built by Passionate Engineers</h2>
    <p class="sec-sub reveal">A team of MCA students from Sikkim Manipal University, driven by the belief that technology should serve everyone — especially those who need it most.</p>
  </div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:22px">
    <?php
    $team = [
      ['Yash','Tripathi','Lead Developer','Full-stack architecture, database design, session tracking engine, and system integration.','av-grad-1','fa-solid fa-code',['PHP','MySQL','JS','Bootstrap']],
      ['Manasvi','Sharma','UI/UX Designer','Crafted every pixel of the interface — from colour palette to accessibility and user flow.','av-grad-2','fa-solid fa-palette',['CSS','Design','Bootstrap','UX']],
      ['Vidhisha','Patel','Backend Engineer','Performance analysis engine, report generation module, and query optimisation.','av-grad-3','fa-solid fa-database',['SQL','Analytics','PHP','Charts']],
      ['Pranay','Mehta','Research & QA','User research with educators, classroom testing, and ensuring real-world usability.','av-grad-4','fa-solid fa-flask',['Research','Testing','UX','Docs']],
    ];
    foreach($team as [$fn,$ln,$role,$desc,$grad,$ic,$tags]): ?>
    <div class="team-card reveal">
      <div class="team-av-wrap">
        <div class="team-av-init <?= $grad ?>">
          <i class="<?= $ic ?>" style="font-size:26px"></i>
        </div>
      </div>
      <div class="team-name"><?= $fn ?> <?= $ln ?></div>
      <div class="team-role"><?= $role ?></div>
      <div class="team-desc"><?= $desc ?></div>
      <div class="team-tags">
        <?php foreach($tags as $tag): ?>
        <span class="badge-blue" style="font-size:10px"><?= $tag ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</div>

<!-- ════════════════ CTA ════════════════ -->
<section class="lp-cta-band">
  <div class="lp-cta-inner">
    <div style="font-size:52px;margin-bottom:18px">🎓</div>
    <div class="sec-eyebrow center reveal" style="display:inline-flex"><i class="fa-solid fa-rocket"></i> Get Started Today</div>
    <h2 style="font-family:'Poppins',sans-serif;font-size:34px;font-weight:900;color:var(--navy);margin:14px 0 12px">
      Ready to Transform Learning?
    </h2>
    <p style="color:var(--muted);font-size:15.5px;margin-bottom:30px;line-height:1.75">
      Join teachers and parents who are tracking progress, building confidence,
      and changing lives — one session at a time.
    </p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="/edutrack/signup.php" class="hero-cta-p">
        <i class="fa-solid fa-user-plus"></i> Create Free Account
      </a>
      <a href="/edutrack/login.php" class="hero-cta-s">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </a>
    </div>
    <div style="margin-top:20px;font-size:13px;color:var(--muted)">
      <i class="fa-solid fa-lock" style="color:var(--accent)"></i> No credit card required &nbsp;·&nbsp;
      <i class="fa-solid fa-bolt" style="color:var(--amber)"></i> Set up in 2 minutes &nbsp;·&nbsp;
      <i class="fa-solid fa-heart" style="color:var(--red)"></i> Made for every child
    </div>
  </div>
</section>

<!-- ════════════════ FOOTER ════════════════ -->
<footer class="lp-footer">
  <div class="lp-footer-inner">
    <div style="display:flex;align-items:center;gap:10px">
      <div style="width:30px;height:30px;background:rgba(255,255,255,.12);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff">
        <i class="fa-solid fa-brain"></i>
      </div>
      <span style="font-family:'Poppins',sans-serif;font-weight:800;color:#fff;font-size:15px">EduTrack<span style="color:var(--accent)">Pro</span></span>
    </div>
    <div>
      Made with <i class="fa-solid fa-heart" style="color:#f87171"></i> for children who learn differently &nbsp;·&nbsp;
      <a href="/edutrack/login.php">Login</a> &nbsp;·&nbsp;
      <a href="/edutrack/signup.php">Sign Up</a>
    </div>
    <div>© <?= date('Y') ?> EduTrack Pro &nbsp;·&nbsp; Sikkim Manipal University</div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/edutrack/js/main.js"></script>
<script>
/* ── TYPEWRITER ── */
const words = ['Shine & Grow','Learn & Thrive','Be Celebrated','Feel Understood'];
let wi=0,ci=0,del=false;
const el=document.getElementById('typed-text');
function type(){
  const word=words[wi];
  if(!del){
    el.textContent=word.slice(0,++ci);
    if(ci===word.length){setTimeout(()=>{del=true;type();},1800);return;}
  } else {
    el.textContent=word.slice(0,--ci);
    if(ci===0){del=false;wi=(wi+1)%words.length;}
  }
  setTimeout(type,del?50:80);
}
type();

/* counter + scroll reveal already in app.js */
</script>
</body>
</html>