function getAddArticleButton() {
    return document.querySelector('#calculator-add');
}

function getArticlesContainer() {
    return document.querySelector(getAddArticleButton().dataset.listSelector);
}

function getArticles() {
    return document.querySelectorAll('.calculator-article');
}

function ShowResult(counter, formData) {
    const resultSpan = document.querySelector(`#article-result-${counter}`);

    const getArticleFieldValue = (name) => parseInt(formData.get(`calculator[articles][${counter}][${name}]`), 10);
    const getFieldValue = (name) => parseInt(formData.get(`calculator[${name}]`), 10);
    const length = getArticleFieldValue('length');
    const width = getArticleFieldValue('width');
    const quantity = getArticleFieldValue('quantity');
    const globalWidth = getFieldValue('width');
    const withdrawWidth = getFieldValue('withdrawWidth');
    const withdrawLength = getFieldValue('withdrawLength');

    if (
        !length
        || !width
        || !quantity
        || !globalWidth
        || length < 0
        || width < 0
        || quantity < 0
        || globalWidth < 0
        || withdrawLength < 0
        || withdrawLength < 0
    ) {
        resultSpan.parentElement.style.display = 'none';
        delete resultSpan.dataset.totalLength;
        return;
    }

    const margin = 2;
    const strip = (globalWidth * (100 - withdrawWidth)) / 100;

    const articlesPerRow = Math.floor((strip - margin) / (width + margin));
    let leftOver = strip - (articlesPerRow * (width + margin));
    leftOver = Math.round(leftOver * 100) / 100;
    const totalLength = (((length * 100) / (100 - withdrawLength)) + margin)
        * 2 * Math.ceil(quantity / articlesPerRow);
    const totalLengthMeters = Math.ceil(totalLength / 100);

    resultSpan.dataset.totalLength = totalLength;
    resultSpan.parentElement.style.removeProperty('display');
    resultSpan.innerHTML = `Longueur: ${totalLengthMeters}m<br>Chutes: ${leftOver}cm`;
}

function ShowTotal(formData) {
    let errors = 0;
    let total = 0;
    document.querySelectorAll('.article-result').forEach((result) => {
        if (result.dataset.totalLength) {
            total += parseInt(result.dataset.totalLength, 10);
        } else {
            errors += 1;
        }
    });

    const resultDiv = document.querySelector('#calculator-results');
    const saveButton = document.querySelector('#calculator-save');
    if (errors) {
        resultDiv.style.display = 'none';
        saveButton.style.display = 'none';
        return;
    }
    resultDiv.style.removeProperty('display');
    saveButton.style.removeProperty('display');

    const totalMeters = Math.ceil(total / 100);
    document.querySelector('#calculator-results > p').innerHTML = `<strong>Longueur totale</strong>: ${totalMeters}m`;
    document.querySelector('#calculator_length').value = total;
}

function RefreshResult(counter) {
    const formData = new FormData(document.querySelector('#calculator-form'));
    if (counter !== null) {
        ShowResult(counter, formData);
    }
    ShowTotal(formData);
}

function createArticleDeleteLink(articleFormElement) {
    const removeFormButton = document.createElement('a');
    removeFormButton.innerText = '✖';
    removeFormButton.href = '#';

    articleFormElement.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (getArticles().length > 1) {
            articleFormElement.remove();
            RefreshResult(null);
        }
    });
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
    newElem.innerHTML += newWidget;

    container.appendChild(newElem);
    newElem.querySelector('legend').textContent = `Article ${counter}`;

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
    const oldOffset = e.target.offsetTop;
    addArticle();
    window.scrollBy(0, e.target.offsetTop - oldOffset);
});

document.querySelector('#calculator-form').addEventListener('input', (event) => {
    const match = event.target.id.match(/^calculator_articles_(?<counter>\d+)/);
    if (!match) {
        return;
    }
    RefreshResult(match.groups.counter);
});

const globalInputs = document.querySelectorAll('#calculator_width, #calculator_withdrawLength, #calculator_withdrawWidth');
globalInputs.forEach((input) => input.addEventListener('input', (event) => {
    getArticles().forEach((article) => {
        const articleDiv = article.querySelector('div');
        const counter = parseInt(articleDiv.id.match(/\d+$/), 10);
        RefreshResult(counter);
    });
}));

document.querySelector('.main-calculator').style.visibility = 'visible';
