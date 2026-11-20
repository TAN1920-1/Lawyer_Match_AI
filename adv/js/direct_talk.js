function toggleDirectTalk() {
    var w = document.getElementById('directTalkWindow');
    if (w) {
        if (w.classList.contains('active')) {
            w.classList.remove('active');
        } else {
            w.classList.add('active');
            var c = document.getElementById('directTalkCode');
            if (c) c.focus();
        }
    }
}
function joinDirectTalk() {
    var code = '';
    var name = '';
    var c = document.getElementById('directTalkCode');
    var n = document.getElementById('directTalkName');
    if (c && c.value) code = c.value.trim();
    if (n && n.value) name = n.value.trim();
    var base = 'https://aetherastra.vercel.app/direct';
    var url = base;
    var params = [];
    if (code) params.push('room=' + encodeURIComponent(code));
    if (name) params.push('name=' + encodeURIComponent(name));
    if (params.length > 0) {
        params.push('mode=direct');
        url = base + '?' + params.join('&');
    }
    window.open(url, '_blank');
    var w = document.getElementById('directTalkWindow');
    if (w) w.classList.remove('active');
}
