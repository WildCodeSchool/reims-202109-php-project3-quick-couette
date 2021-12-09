function getAddArticleButton() {
    return document.querySelector('#calculator-add');
}

function getArticleList() {
    return document.querySelector(getAddArticleButton().dataset.listSelector);
}

function getArticles() {
    return document.querySelectorAll('.calculator-article');
}

function addArticleDeleteLink(articleFormElement) {
    const removeFormButton = document.createElement('a');
    removeFormButton.innerText = '✖';

    articleFormElement.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (getArticles().length > 1) {
            articleFormElement.remove();
        }
    });
}

function addArticle() {
    const list = getArticleList();

    let counter = list.dataset.widgetCounter || list.childElementCount;
    const newWidget = list.dataset.prototype.replace(/__name__/g, counter);
    counter += 1;
    list.dataset.widgetCounter = counter;

    let newElem = document.createElement('div');
    newElem.innerHTML = list.dataset.widgetTags;
    newElem = newElem.firstChild;
    newElem.innerHTML = newWidget;
    list.appendChild(newElem);

    addArticleDeleteLink(newElem);
}

const articles = getArticles();
articles.forEach((article) => {
    addArticleDeleteLink(article);
});

if (!articles.length) {
    addArticle();
}

getAddArticleButton().addEventListener('click', (e) => {
    addArticle();
});
