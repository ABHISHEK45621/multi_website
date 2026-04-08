// ================= CONFIG =================
let session_id = new URLSearchParams(window.location.search).get("session_id");
let exam_id = new URLSearchParams(window.location.search).get("exam_id");

let tabSwitchCount = 0;

// ================= TAB SWITCH DETECTION =================
document.addEventListener("visibilitychange", function () {
    if (document.hidden) {

        tabSwitchCount++;

        // Log to server
        fetch("../ajax/log_event.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `session_id=${session_id}&event=tab_switch`
        });

        // Update tab count
        fetch("../ajax/update_tab.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `session_id=${session_id}`
        });

        alert("⚠ Tab switch detected! Exam will be submitted.");

        // AUTO SUBMIT AFTER 1 SWITCH
        submitExam();
    }
});

// ================= COPY PASTE BLOCK =================
document.addEventListener("copy", e => e.preventDefault());
document.addEventListener("paste", e => e.preventDefault());
document.addEventListener("cut", e => e.preventDefault());

// ================= FULLSCREEN =================
function enterFullscreen() {
    let elem = document.documentElement;

    if (elem.requestFullscreen) elem.requestFullscreen();
}

enterFullscreen();

// Detect exit fullscreen
document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) {

        alert("⚠ Fullscreen exited! Exam will be submitted.");

        fetch("../ajax/log_event.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `session_id=${session_id}&event=fullscreen_exit`
        });

        submitExam();
    }
});

// ================= SUBMIT FUNCTION =================
function submitExam() {

    fetch("../student/submit_exam.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `exam_id=${exam_id}&session_id=${session_id}`
    })
    .then(res => res.json())
    .then(data => {
        window.location.href = "../student/result.php";
    });
}