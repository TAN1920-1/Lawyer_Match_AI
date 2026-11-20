<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';

$developers = [
    [
        'name' => 'Tanishq',
        'title' => 'The Feature Alchemist',
        'image' => 'tanishq.png',
        'emoji' => '🧠',
        'tagline' => 'Chill by nature, chaotic by code.',
        'description' => 'Tanishq is the Gen-Z brain of the backend, constantly cooking up feature improvements like a chef in a high-tech kitchen. If a feature exists, he’ll improve it. If it doesn’t exist, he’ll probably build it by midnight.',
        'extra' => 'He’s the one quietly maintaining the database universe, making sure data behaves and doesn’t start a rebellion. Calm, creative, and dangerously productive when caffeine enters the system.',
        'specialties' => ['Feature engineering', 'Database management', 'Backend improvements', 'Turning “what if…” ideas into real features'],
        'social' => ['github' => '#', 'linkedin' => '#', 'discord' => '#']
    ],
    [
        'name' => 'Jeet',
        'title' => 'The Tech Athlete',
        'image' => 'jeet.png',
        'emoji' => '⚡',
        'tagline' => 'Half developer, half power-plant.',
        'description' => 'Jeet is a tech enthusiast powered by code and protein shakes. When he’s not debugging something, he’s probably debugging his workout routine at the gym.',
        'extra' => 'He’s the core mind behind AetherAstra’s chat application development, making sure communication flows smoother than his workout playlist. Connectivity, new features, performance improvements—Jeet handles it like a champ.',
        'specialties' => ['Chat application development', 'System connectivity', 'Feature additions', 'Performance improvements'],
        'social' => ['github' => '#', 'linkedin' => '#', 'discord' => '#']
    ],
    [
        'name' => 'Spandan',
        'title' => 'The Creative Engine',
        'image' => 'spandan.png',
        'emoji' => '🍔',
        'tagline' => 'Food lover, gamer, and secretly the most overloaded worker.',
        'description' => 'Spandan might look relaxed, but he’s actually the multi-role powerhouse of the team. From designing the UI, contributing to development, doing testing, writing documentation, and handling admin tasks, he basically touches almost every part of the project.',
        'extra' => 'Some say his productivity increases after snacks and gaming breaks.',
        'specialties' => ['UI Design', 'Development support', 'Documentation (main handler)', 'Basic testing', 'Admin management'],
        'social' => ['github' => '#', 'linkedin' => '#', 'discord' => '#']
    ],
    [
        'name' => 'Sarvesh',
        'title' => 'The Silent Architect',
        'image' => 'sarvesh.png',
        'emoji' => '🎨',
        'tagline' => 'Always offline… but somehow everything works.',
        'description' => 'Sarvesh is the project lead and quiet mastermind behind the scenes. Often sleepy, sometimes invisible, but always thinking about how to improve the project.',
        'extra' => 'He manages admin responsibilities, testing, improvements, and keeps the team aligned—even if he replies three hours later. If something needs fixing, improving, or testing deeply… Sarvesh is already thinking about it.',
        'specialties' => ['Project leadership', 'Core testing', 'Feature improvements', 'Admin management'],
        'social' => ['github' => '#', 'linkedin' => '#', 'discord' => '#']
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/modern_style.css">
    <title>About Us | StudyStack Style</title>
    <style>
        :root {
            --dark-bg: var(--bg-color);
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent: var(--primary-color);
        }
        body { 
            background-color: var(--bg-color); 
            color: var(--text-main);
            font-family: 'Inter', -apple-system, sans-serif;
            padding-bottom: 50px;
        }
        .navbar { background: white !important; border-bottom: 1px solid rgba(0,0,0,0.05); }
        
        .about-header {
            padding: 80px 0 40px;
        }
        .about-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
        }
        .dev-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .dev-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .dev-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        .dev-img-container {
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #f1f5f9;
        }
        .dev-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }
        .dev-content {
            padding: 25px;
            flex-grow: 1;
        }
        .dev-header-info {
            margin-bottom: 15px;
        }
        .dev-name-box h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-main);
        }
        .dev-badge {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .dev-tagline {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-style: italic;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 3px solid var(--accent);
        }
        .dev-bio {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 20px;
        }
        .dev-extra {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 20px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .spec-list {
            margin-bottom: 10px;
        }
        .spec-title {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 10px;
            display: block;
        }
        .spec-item {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .dev-footer {
            padding: 15px 25px;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dev-socials a {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin: 0 10px;
            transition: all 0.2s;
        }
        .dev-socials a:hover {
            color: var(--accent);
            transform: scale(1.1);
        }
        
        @media (max-width: 768px) {
            .about-header h1 { font-size: 2rem; }
            .dev-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <div class="about-header text-center">
            <h4 class="text-primary mb-3">👨‍💻 Meet the Devs</h4>
            <h1>About Us</h1>
        </div>

        <div class="dev-grid">
            <?php foreach ($developers as $dev): ?>
                <div class="dev-card">
                    <div class="dev-img-container">
                        <?php 
                            $img_path = "images/developers/" . $dev['image'];
                            // Check if file exists, else use a fallback avatar style based on StudyStack
                            if (!file_exists($img_path)) {
                                $img_path = "images/actors/lawyer-working-document-with-scales-justice.jpg"; 
                            }
                        ?>
                        <img src="<?php echo $img_path; ?>" alt="<?php echo $dev['name']; ?>">
                    </div>
                    <div class="dev-content">
                        <div class="dev-header-info">
                            <div class="dev-name-box">
                                <h3><?php echo $dev['emoji'] . ' ' . $dev['name']; ?></h3>
                                <span class="dev-badge"><?php echo $dev['title']; ?></span>
                            </div>
                        </div>
                        
                        <div class="dev-tagline"><?php echo $dev['tagline']; ?></div>
                        
                        <div class="dev-bio">
                            <?php echo $dev['description']; ?>
                        </div>

                        <?php if($dev['extra']): ?>
                        <div class="dev-extra">
                            <?php echo $dev['extra']; ?>
                        </div>
                        <?php endif; ?>

                        <div class="spec-list">
                            <span class="spec-title">Specialties</span>
                            <?php foreach ($dev['specialties'] as $spec): ?>
                                <span class="spec-item"><?php echo $spec; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="dev-footer">
                        <div class="dev-socials">
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-discord"></i></a>
                            <a href="mailto:lawyermatch.ai@gmail.com"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
