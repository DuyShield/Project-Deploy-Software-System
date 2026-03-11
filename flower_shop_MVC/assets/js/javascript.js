//Mở hoặc đóng thanh tìm kiếm
function toggleSearch(){
    let search = document.getElementById("searchInput");

    if(search.style.display === "none"){
        search.style.display = "block";
    }else{
        search.style.display = "none";
    }
}
//Đếm lùi thời gian sale
let totalSeconds = 7 * 3600 + 4 * 60 + 29;
function updateCountdown(){
    totalSeconds--;
    if(totalSeconds < 0) totalSeconds = 0;
    let hours = Math.floor(totalSeconds / 3600);
    let minutes = Math.floor((totalSeconds % 3600) / 60);
    let seconds = totalSeconds % 60;
    document.getElementById("countdown").innerText =
        String(hours).padStart(2,'0') + " : " +
        String(minutes).padStart(2,'0') + " : " +
        String(seconds).padStart(2,'0');
}

setInterval(updateCountdown,1000);