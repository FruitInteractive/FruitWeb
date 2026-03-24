<?php
session_start();
require_once __DIR__ . '/config/webhook.php';

if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: /careers");
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, 'index.php') !== false) {
    $newUrl = str_replace('index.php', '', $requestUri);
    header("Location: $newUrl", true, 301);
    exit;
}

$jobs = [
    [
        "id" => "management",
        "title" => "Operations Team",
        "description" => "Help oversee staff, guide team members, and support the community by ensuring operations run smoothly, assisting with moderation decisions, and helping maintain a positive, organized, and welcoming environment across the entire server.",
        "open" => false
    ],
    [
        "id" => "support",
        "title" => "Support Team",
        "description" => "Assist members by answering questions, resolving issues, and providing guidance while helping maintain a friendly and welcoming environment, ensuring users receive helpful support and a smooth experience across the entire server.",
        "open" => true
    ]
];

$success = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = array_map('htmlspecialchars', $_POST);

    $required = ['age','timezone','country','why_join','why_select','activity','join_date','found_server','experience','excited','extra'];
    $valid = true;

    foreach ($required as $field) {
        if(empty($data[$field])) {
            $valid = false;
            break;
        }
    }

    $fields_with_limits = ['why_join','why_select','excited'];
    foreach($fields_with_limits as $field){
        $len = strlen($data[$field]);
        if($len < 100 || $len > 250){
            $valid = false;
            break;
        }
    }

    $age = intval($data['age']);
    if($age < 14 || $age > 50){
        $valid = false;
    }

    if($valid && isset($_SESSION['discord_user'])) {

        $discordUsername = explode('#', $_SESSION['discord_user']['username'])[0];
        $discordID = $_SESSION['discord_user']['id'];

        $payload = [
            "username" => "Fruit Interactive - Applications",
            "embeds" => [[
                "title" => "New Application Received",
                "color" => hexdec("000000"),
                "fields" => [
                    ["name"=>"Position","value"=>$data['position']],
                    ["name"=>"Discord Username","value"=>$discordUsername,"inline"=>true],
                    ["name"=>"Discord ID","value"=>$discordID,"inline"=>true],
                    ["name"=>"Age","value"=>$data['age'],"inline"=>true],
                    ["name"=>"Timezone","value"=>$data['timezone'],"inline"=>true],
                    ["name"=>"Country","value"=>$data['country'],"inline"=>true],
                    ["name"=>"Why join?","value"=>$data['why_join']],
                    ["name"=>"Why select you?","value"=>$data['why_select']],
                    ["name"=>"Discord Activity","value"=>$data['activity']],
                    ["name"=>"When joined server","value"=>$data['join_date']],
                    ["name"=>"How found server","value"=>$data['found_server']],
                    ["name"=>"Previous support experience","value"=>$data['experience']],
                    ["name"=>"Excited about role","value"=>$data['excited']],
                    ["name"=>"Additional comments","value"=>$data['extra']]
                ],
                "footer"=>["text"=>"Fruit Interactive Recruitment System"],
                "timestamp"=>date("c")
            ]]
        ];

        $ch = curl_init($DISCORD_WEBHOOK);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpCode === 204) {
            $success = true;
            header("Location: ".$_SERVER['PHP_SELF']."?submitted=1");
            exit;
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fruit Interactive — Careers</title>
<link rel="stylesheet" href="/assets/css/careers.css">
<link rel="icon" type="image/png" href="/assets/images/Lemon.png">
</head>
<body>

<div class="navbar">
    <div class="nav-left"></div>
    <img class="logo" src="/assets/images/Logo.png" alt="Racoon Logo">
    <div class="nav-right">
        <?php if(isset($_SESSION['discord_user'])): ?>
            <img src="<?php echo $_SESSION['discord_user']['avatar_url']; ?>" alt="Avatar" class="discord-avatar" onclick="toggleLogoutCard()">
        <?php else: ?>
            <a href="login.php" class="discord-login-btn">Login with Discord</a>
        <?php endif; ?>
    </div>
</div>

<div class="logout-card" id="logoutCard">
    <a href="?logout=1">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
        </svg>
        Logout
    </a>
</div>

<div class="main-content">

<?php if($success || isset($_GET['submitted'])): ?>
<div id="approved-alert" class="show"><span></span>Application submitted successfully.</div>
<?php endif; ?>

<?php if($error): ?>
<div id="closed-alert" class="show"><span></span>Please login and complete all required fields (check character limits 100–250 & age 14–50).</div>
<?php endif; ?>

<div class="container">
<?php foreach ($jobs as $job): ?>
<div class="card <?php echo $job['open'] ? '' : 'closed'; ?>" onclick="handleCardClick('<?php echo $job['id']; ?>', <?php echo $job['open'] ? 'true' : 'false'; ?>)">
<span class="status <?php echo $job['open'] ? 'open' : 'closed'; ?>">
<?php echo $job['open'] ? 'OPEN' : 'CLOSED'; ?>
</span>
<h2><?php echo $job['title']; ?></h2>
<p><?php echo $job['description']; ?></p>
</div>
<?php endforeach; ?>
</div>

</div>

<div class="footer">
<img src="/assets/images/Lemon.png" alt="Logo">
© 2026 — Carl Cardnelle
</div>

<?php foreach ($jobs as $job): ?>
<div class="modal" id="<?php echo $job['id']; ?>">
<div class="modal-content">
<div class="close" onclick="closeModal('<?php echo $job['id']; ?>')">✖ Close</div>
<h2><?php echo $job['title']; ?> Application</h2>

<form method="POST">
<input type="hidden" name="position" value="<?php echo $job['title']; ?>">

<div class="step active">
<input name="age" type="number" placeholder="Age? (14–50)" min="14" max="50" required>
<input name="timezone" placeholder="Timezone?" required>
<input name="country" placeholder="Country?" required>
<button type="button" onclick="nextStep(this)">Next</button>
</div>

<div class="step">
<textarea name="why_join" placeholder="Why do you want to join this position?" required minlength="100" maxlength="250"></textarea>
<textarea name="why_select" placeholder="Why do you think we should select you?" required minlength="100" maxlength="250"></textarea>
<input name="activity" placeholder="How active are you on discord?" required>
<input name="join_date" placeholder="When did you join our server?" required>
<input name="found_server" placeholder="How did you find our server?" required>
<input name="experience" placeholder="Do you have previous experience as support?" required>
<button type="button" onclick="nextStep(this)">Next</button>
</div>

<div class="step">
<textarea name="excited" placeholder="What are you most excited about in your role?" required minlength="100" maxlength="250"></textarea>
<textarea name="extra" placeholder="Is there anything else you'd like to add or comment on?" required></textarea>
<button type="submit">Submit Application</button>
</div>

</form>
</div>
</div>
<?php endforeach; ?>

<div id="closed-alert"><span></span></div>
<div id="approved-alert"><span></span></div>

<script>
function toggleLogoutCard(){
const card = document.getElementById('logoutCard');
card.style.display = card.style.display === 'block' ? 'none' : 'block';
}

function openModal(id){ document.getElementById(id).style.display="flex"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }

function nextStep(btn){
let step = btn.closest(".step");
let inputs = step.querySelectorAll("input, textarea");

for(let i=0;i<inputs.length;i++){
if(!inputs[i].value){ inputs[i].reportValidity(); return; }

if(inputs[i].name === 'age'){
const age = parseInt(inputs[i].value);
if(age < 14 || age > 50){ inputs[i].reportValidity(); return; }
}

if(['why_join','why_select','excited'].includes(inputs[i].name)){
const len = inputs[i].value.length;
if(len < 100 || len > 250){ inputs[i].reportValidity(); return; }
}
}

step.classList.remove("active");
let next = step.nextElementSibling;
if(next) next.classList.add("active");
}

function handleCardClick(id, open){
if(!open){
const alert = document.getElementById('closed-alert');
alert.innerHTML = '<span></span>This application is closed';
alert.classList.add('show');
setTimeout(()=>alert.classList.remove('show'), 5000);
return;
}

<?php if(!isset($_SESSION['discord_user'])): ?>
const alert = document.getElementById('closed-alert');
alert.innerHTML = '<span></span>Please login to begin your application process';
alert.classList.add('show');
setTimeout(()=>alert.classList.remove('show'), 5000);
<?php else: ?>
openModal(id);
<?php endif; ?>
}

document.querySelectorAll('textarea[name="why_join"], textarea[name="why_select"], textarea[name="excited"]').forEach(textarea => {
const counter = document.createElement('div');
counter.style.fontSize = '12px';
counter.style.color = '#ccc';
counter.style.marginTop = '4px';
textarea.parentNode.insertBefore(counter, textarea.nextSibling);

textarea.addEventListener('input', () => {
const len = textarea.value.length;
counter.textContent = `${len}/250 characters`;

if(len < 100 || len > 250){
textarea.style.borderColor = 'red';
}else{
textarea.style.borderColor = '#4fd3ff';
}
});
});

window.addEventListener('DOMContentLoaded', () => {
['approved-alert','closed-alert'].forEach(id => {
const alert = document.getElementById(id);
if(alert.classList.contains('show')){
setTimeout(() => alert.classList.remove('show'), 5000);
}
});
});
</script>

</body>
</html>
