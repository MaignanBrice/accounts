/* ASYNCHRONOUS CRUD */


document.querySelector('.js-table').addEventListener('click', (event) => {
    if(!event.target.classList.contains('js-delete-btn')) return;
    fetchAction(event.target).then(datas => {
        if(datas.output) {
            switch (event.target.dataset.action) {
                case 'delete':
                    event.target.closest('tr').remove();
                    document.querySelector('.js-current-balance').innerHTML = `${datas.balance} €`;  
                    createNotif('notif', datas.action);
                    break;
            }
        }
    })
})







/* JAVASCRIPT FUNCTIONS */


/* Return the current token */
function getToken() {
    return document.body.dataset.token;
}

/* Return the JSON file depending of the clicked node  */
function fetchAction(node) {
    let token = getToken();
    if (node.dataset.id !== undefined && token.length > 1) {
        let id = parseInt(node.dataset.id);
        return fetch(`api.php?action=${node.dataset.action}&id=${id}&token=${token}`)
            .then(response => response.json());
    };
}


function createNotif(type, content) {
    const notif_node = document.getElementById(type);
    notif_node.classList.remove('visually-hidden');
    notif_node.innerText = content;
    notifTimer(type);
}

function notifTimer(notiftype) {
    if (!document.querySelector(`#${notiftype}`)) return;
    setTimeout(function () {
        document.querySelector(`#${notiftype}`).classList.add('visually-hidden');
    }, 2000);
};