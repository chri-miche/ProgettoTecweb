/* TODO
*       eliminare, questo script modifica il layout: layout e comportamento devono essere separati
* */

const personal = document.getElementById("personal-content");
const discussed = document.getElementById("discussed-content");
const popular = document.getElementById("popular-content");

const personalHeader = document.getElementById("personal-header");
const discussedHeader = document.getElementById("discussed-header");
const popularHeader = document.getElementById("popular-header");

const heightTabHeaders = "50px";

(() => {
    // Init css properties che dovrebbero esserci solo se il js è abilitato
    const container = document.getElementById("feed-tab");
    container.style.display = "flex";
    personal.style.position = "absolute";
    discussed.style.position = "absolute";
    popular.style.position = "absolute";
    personal.style.marginTop = heightTabHeaders;
    discussed.style.marginTop = heightTabHeaders;
    popular.style.marginTop = heightTabHeaders;

    personalHeader.style.height = heightTabHeaders;
    discussedHeader.style.height = heightTabHeaders;
    popularHeader.style.height = heightTabHeaders;

    personalHeader.innerHTML = `
        <button onclick="showTab(personal)">` + personalHeader.innerText + `</button>
    `;
    popularHeader.innerHTML = `
        <button onclick="showTab(popular)">` + popularHeader.innerText + `</button>
    `;
    discussedHeader.innerHTML = `
        <button onclick="showTab(discussed)">` + discussedHeader.innerText + `</button>
    `;

    // init tabs
    showTab(personal);
})()

function hideAllTabs() {
    personal.style.display = "none";
    discussed.style.display = "none";
    popular.style.display = "none";
}

function showTab(tab) {
    hideAllTabs();
    tab.style.display = "block";
}