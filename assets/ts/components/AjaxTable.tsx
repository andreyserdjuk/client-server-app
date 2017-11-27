import * as React from "react";
import * as ReactDOM from "react-dom";
// import {DebounceInput} from 'react-debounce-input';
const DebounceInput = require('react-debounce-input');

export interface RowInterface {
    [key:string]: any,
}

export interface ColumnInterface {
    dataRowKey: string,
    filterType: string,
    filterName: string,
}

export interface AjaxResponse<DataElement> {
    total: number,              // total results number
    data: Array<DataElement>    // array of any data
}

export interface DataFetcherInterface {
    (
        filters: {[key:string]:string},
        pageSize: number,
        currentPage: number
    ): Promise<AjaxResponse<RowInterface>>
}

export interface TableState {
    pageSize: number,                       // elements per page
    currentPage: number,                    // current page
    filterValues: {[key:string]:string},    // key - column name, value - related input filter value
    data: Array<{[key:string]:string}>,
    totalPages: number,                     // total pages number
    loading: boolean,
}

interface TableProps {
    dataFetcher: DataFetcherInterface,
    columns: Array<ColumnInterface>,
    pageSize: number,
}

// next 2 intefaces - to cast event.target.value
interface InputTarget extends HTMLInputElement {
    value: string;
}
interface InputFormEvent<T> extends React.FormEvent<T> {
    target: InputTarget
}

export class AjaxTable extends React.Component<TableProps, TableState> {
    constructor(props:TableProps) {
        super(props);
        this.prevPage = this.prevPage.bind(this);
        this.nextPage = this.nextPage.bind(this);
        this.onFilterChange = this.onFilterChange.bind(this);
        this.renderFilters = this.renderFilters.bind(this);
        this.onPageNumChange = this.onPageNumChange.bind(this);
        this.state = {
            pageSize: this.props.pageSize,           // elements per page
            currentPage: 1,         // current page
            filterValues: {},
            data: [],
            totalPages: 0,          // total pages number
            loading: true,          // data is not loaded - freeze view or notify user
        };
    }

    componentDidMount() {
        this.props.dataFetcher(
            {},
            this.props.pageSize,
            1
        ).then(response => {
            const totalPages = Math.ceil(response.total / this.props.pageSize);
            const data = response.data;
            this.setState({
                data: data,
                totalPages: totalPages,          // total pages number
                loading: false,          // data is not loaded - freeze view or notify user
            });
        });
    }

    onFilterChange(e:InputFormEvent<HTMLInputElement>) {
        const targetValue = e.target.value;
        const targetName = e.target.name;
        const filterValues = Object.assign({}, {...this.state.filterValues, [targetName]: targetValue});
        this.setState({loading: true});
        this.props.dataFetcher(
            filterValues,
            this.state.pageSize,
            this.state.currentPage
        ).then(response => {
            const totalPages = Math.ceil(response.total / this.state.pageSize);
            const data = response.data;
            this.setState({currentPage:1, filterValues, data, totalPages, loading: false});
        });
    }

    onPageNumChange(e:InputFormEvent<HTMLInputElement>) {
        if (!e.target.value) {
            return;
        }
        
        let currentPage:number;
        currentPage = Number.parseInt(e.target.value);

        if (currentPage > this.state.totalPages || currentPage < 1 || currentPage === this.state.currentPage) {
            e.target.value = this.state.currentPage.toString();
            return;
        }

        this.setState({loading: true});
        this.props.dataFetcher(
            this.state.filterValues,
            this.state.pageSize,
            currentPage
        ).then(response => {
            const totalPages = Math.ceil(response.total / this.state.pageSize);
            const data = response.data;
            this.setState({data, totalPages, currentPage, loading: false});
        });
    }

    prevPage() {
        if (this.state.currentPage > 1) {
            const currentPage = this.state.currentPage - 1;
            this.setState({loading: true});
            this.props.dataFetcher(
                this.state.filterValues,
                this.state.pageSize,
                currentPage
            ).then(response => {
                const totalPages = Math.ceil(response.total / this.state.pageSize);
                const data = response.data;
                this.setState({data, totalPages, currentPage, loading: false});
            });
        }
    }

    nextPage() {
        if (this.state.currentPage+1 > this.state.totalPages) {
            return;
        }

        const currentPage = this.state.currentPage + 1;

        this.setState({loading: true});
        this.props.dataFetcher(
            this.state.filterValues,
            this.state.pageSize,
            currentPage
        ).then(response => {
            const totalPages = Math.ceil(response.total / this.state.pageSize);
            const data = response.data;
            this.setState({data, totalPages, currentPage, loading: false});
        });
    }

    render() {
        const Row = (props:{data:RowInterface}) => {
            const row:RowInterface = props.data;
            return (
                <tr>{this.props.columns.map(col => <td key={col.dataRowKey}>{row[col.dataRowKey]}</td>)}</tr>
            );
        };

        return (
            <div>
                <table className={'table table-striped table-bordered table-hover'}>
                    <thead><tr>{ this.renderFilters() }</tr></thead>
                    <tbody>{ this.state.data.map((rowData:RowInterface) => <Row key={rowData.transaction_id} data={rowData}/>) }</tbody>
                </table>
                <div>Total pages: {this.state.totalPages}</div>

                <div className={"row"}>
                <div className={"col-lg-2 col-sm-3 col-md-3"}>
                    <div className={"input-group"}>
                        <div className={"input-group-btn"}>
                            <DebounceInput
                                type="number"
                                className={"form-control"}
                                debounceTimeout={300}
                                value={this.state.currentPage} 
                                onChange={this.onPageNumChange} />
                            <button type={"button"} className={"btn btn-default"} onClick={this.prevPage}>prev</button>
                            <button type={"button"} className={"btn btn-default"} onClick={this.nextPage}>next</button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        );
    }

    renderFilters() {
        return this.props.columns.map(col => {
            if (col.filterType === 'text' || col.filterType === 'date') {
                return (
                    <th key={col.dataRowKey}>
                        <DebounceInput
                            name={col.filterName}
                            type={col.filterType}
                            onChange={this.onFilterChange}
                            debounceTimeout={300}
                        />
                    </th>
                );
            }
            return <th key={col.dataRowKey}></th>;
        });
    }
}
