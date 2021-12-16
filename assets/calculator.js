function getAddArticleButton() {
    return document.querySelector('#calculator-add');
}

function getArticlesContainer() {
    return document.querySelector(getAddArticleButton().dataset.listSelector);
}

function getArticles() {
    return document.querySelectorAll('.calculator-article');
}

function createArticleDeleteLink(articleFormElement) {
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

function getArticleValues(formData, counter) {
    const getFieldValue = (name) => parseInt(formData.get(`calculator[articles][${counter}][${name}]`), 10);
    const length = getFieldValue('length');
    const width = getFieldValue('width');
    const quantity = getFieldValue('quantity');
    return [length, width, quantity];
}

function ShowResult(counter, formData) {
    const resultSpan = document.querySelector(`#article-result-${counter}`);

    const [length, width, quantity] = getArticleValues(formData, counter);
    if (!length || !width || !quantity) {
        resultSpan.parentElement.style.display = 'none';
        return;
    }

    const margin = 2;
    const strip = 290;

    const articlesPerRow = Math.floor((strip - margin) / (width + margin));
    const leftOver = strip - (articlesPerRow * (width + margin));
    const totalLength = (length + margin) * 2 * quantity;

    resultSpan.dataset.totalLength = totalLength;
    resultSpan.parentElement.style.removeProperty('display');
    resultSpan.innerHTML = `Longueur: ${totalLength}cm<br>Chutes: ${leftOver}cm<br>(${length} + ${margin}) * 2 * ${quantity}`;
}

function ShowTotal(formData) {
    let total = 0;
    document.querySelectorAll('.article-result').forEach((result) => {
        if (result.dataset.totalLength) {
            total += parseInt(result.dataset.totalLength, 10);
        }
    });

    const resultDiv = document.querySelector('#calculator-results');
    if (!total) {
        resultDiv.style.display = 'none';
        return;
    }
    resultDiv.style.removeProperty('display');

    // TODO: Format output
    document.querySelector('#calculator-results > p').innerHTML = `Longueur: ${total}cm`;
    document.querySelector('#calculator_length').value = total;
}

function RefreshResult(counter) {
    const formData = new FormData(document.querySelector('#calculator-form'));
    ShowResult(counter, formData);
    ShowTotal(formData);
}

function createResultOutput(articleFormElement) {
    const articleDiv = articleFormElement.querySelector('div');
    const counter = parseInt(articleDiv.id.match(/\d+$/), 10);

    const newElem = document.createElement('div');
    newElem.innerHTML = `
        <span>Résultat</span>
        <output id="article-result-${counter}" class="article-result"></output>
    `;
    articleDiv.appendChild(newElem);

    RefreshResult(counter);
}

function addArticle() {
    const container = getArticlesContainer();

    let counter = parseInt(container.dataset.widgetCounter || container.childElementCount, 10);
    const newWidget = container.dataset.prototype.replace(/__name__/g, counter);
    counter += 1;
    container.dataset.widgetCounter = counter;

    let newElem = document.createElement('div');
    newElem.innerHTML = container.dataset.widgetTags;
    newElem = newElem.firstChild;
    newElem.innerHTML = newWidget;

    container.appendChild(newElem);

    createArticleDeleteLink(newElem);
    createResultOutput(newElem);
}

const articles = getArticles();
articles.forEach((article) => {
    createArticleDeleteLink(article);
    createResultOutput(article);
});

if (!articles.length) {
    addArticle();
}

getAddArticleButton().addEventListener('click', (e) => {
    addArticle();
});

document.querySelector('#calculator-form').addEventListener('input', (event) => {
    const match = event.target.id.match(/^calculator_articles_(?<counter>\d+)/);
    if (!match) {
        return;
    }
    RefreshResult(match.groups.counter);
});
