import * as React from "react";
import * as ReactDOM from "react-dom";
import {AjaxTable, DataFetcherInterface, AjaxResponse, RowInterface, ColumnInterface} from "./components/AjaxTable";

let DataAjaxFetcher: DataFetcherInterface;
DataAjaxFetcher = (
    filters: {[key:string]:string},
    pageSize: number,
    currentPage: number
) => {
    let query = [];
    for(let k in filters) {
        if (filters[k]) {
            query.push(encodeURIComponent(k)+'='+encodeURIComponent(filters[k]));
        }
    }
    let offset = pageSize * (currentPage-1);
    query.push(encodeURIComponent('app_transaction_filters[offset]')+'='+offset);
    query.push(encodeURIComponent('app_transaction_filters[limit]')+'='+pageSize);
    
    return fetch(
        `/api/transaction?${query.join('&')}`,
        {credentials: 'same-origin', mode: 'same-origin'}
    )
    .then((data: Response) => {
        return data.json();
    })
    .then((data:any):Promise<AjaxResponse<RowInterface>> => {
        if (!Object.prototype.hasOwnProperty.call(data, 'total') || !Object.prototype.hasOwnProperty.call(data, 'data')) {
            console.warn('response data has to "total" or "data" key');
            return Promise.resolve({total:0, data:[]});
        }

        return Promise.resolve(data);
    }).catch(() => Promise.resolve({total:0, data:[]}));
};

let columns:Array<ColumnInterface>;
columns = [
    {
        dataRowKey: 'transaction_id',
        filterType: null,
        filterName: null,
    },
    {
        dataRowKey: 'customer_id',
        filterType: 'text',
        filterName: 'app_transaction_filters[customer]',
    },
    {
        dataRowKey: 'amount',
        filterType: 'text',
        filterName: 'app_transaction_filters[amount]',
    },
    {
        dataRowKey: 'date',
        filterType: 'date',
        filterName: 'app_transaction_filters[date]',
    },
];


ReactDOM.render(
    <AjaxTable
        dataFetcher={DataAjaxFetcher}
        columns={columns}
        pageSize={10}
    />,
    document.getElementById("application")
);