"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const React = require("react");
const ReactDOM = require("react-dom");
const AjaxTable_1 = require("./components/AjaxTable");
let DataAjaxFetcher;
DataAjaxFetcher = (filters, pageSize, currentPage) => {
    for (let [k, v] of Object.entries(filters)) {
    }
    return fetch(`//api/transactions?app_transaction_filters[offset]=${page}&app_transaction_filters[limit]=${pageSize}`, { credentials: 'same-origin', mode: 'same-origin' })
        .then((data) => {
        return data.json();
    })
        .then((data) => {
        console.assert(Object.prototype.hasOwnProperty.call(data, 'total'), 'response data has to "total" key');
        console.assert(Object.prototype.hasOwnProperty.call(data, 'data'), 'response data has to "total" key');
        return Promise.resolve(data);
    });
};
const columns = [{
        Header: 'Name',
        accessor: 'name' // String-based value accessors!
    }, {
        Header: 'Age',
        accessor: 'age',
        Cell: (props) => React.createElement("button", { className: 'number' }, props.value) // Custom cell components!
    }];
ReactDOM.render(React.createElement(AjaxTable_1.AjaxTable, { dataFetcher: true }), document.getElementById("application"));
//# sourceMappingURL=index.js.map