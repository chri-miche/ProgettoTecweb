
document.querySelectorAll("table").forEach(table => {

    const thead = table.querySelector("thead");
    const headers = thead?.querySelectorAll("th");

    if (headers) {
        for (let i = 0; i < headers.length; i++) {
            if (headers[i].getAttribute("class") !== "action")
                headers[i].innerHTML +=
                    '<span ' +
                    'class="ordinatore" ' +
                    'data-header-sort-direction="asc"' +
                    'data-header-index="' + i +
                    '">&darr;</span>';
        }
        thead.addEventListener("click", (event) => {
            if (event.target.getAttribute("class") === "ordinatore") {
                sortTable(
                    table,
                    parseInt(event.target.getAttribute("data-header-index")),
                    event.target.getAttribute("data-header-sort-direction")
                );
            }
        });
    }
})

const option = document.getElementById("password-login");
option.innerHTML +='<button id="pw-button" type="button" class="icon-button" data-icon-name="visibility" title="Mostra" onclick="togglePasswordVisibility()"> <span> Mostra </span></button>';

function sortTable(table, headerIndex, direction) {
    const dir = direction === 'asc' ? 1 : -1;
    let rows = Array.from(table.tBodies[0].rows);

    rows.sort((row1, row2) => {
            const x = row1.getElementsByTagName('td')[headerIndex].innerHTML.toLowerCase();
            const y = row2.getElementsByTagName('td')[headerIndex].innerHTML.toLowerCase();

            const nx = parseFloat(x);
            const ny = parseFloat(y);

            if (!isNaN(nx) && !isNaN(ny)) {
                return dir * (nx - ny);
            } else {
                return dir * (x > y ? 1 : (x < y ? -1 : 0));
            }
        }
    )
    rows.forEach(row => {
        table.querySelector("tbody").appendChild(row);
    });

    const thead = table.querySelector("thead");
    const header = thead?.querySelectorAll("th")[headerIndex];
    if (header) {
        header
            .querySelector("span.ordinatore")
            .setAttribute("data-header-sort-direction", dir === 1 ? "desc" : "asc");
        header
            .querySelector("span.ordinatore")
            .innerHTML = dir === 1 ? "&uarr;" : "&darr;";
    }

}

function togglePasswordVisibility() {

    const password = document.getElementById("password");
    const visualization = document.getElementById("pw-button");

    if(password.type === "password"){

        password.type = "text";
        visualization.setAttribute("data-icon-name", "visibility_off");
        visualization.setAttribute("title", "Nascondi");
        visualization.innerHTML = visualization.innerHTML.replace("Mostra", "Nascondi")

    } else{

        password.type = password.type === "password" ? "text" : "password";
        visualization.setAttribute("data-icon-name", "visibility");
        visualization.setAttribute("title", "Mostra");
        visualization.innerHTML = visualization.innerHTML.replace("Nascondi", "Mostra")

    }
}

