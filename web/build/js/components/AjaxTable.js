"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const React = require("react");
class AjaxTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            pageSize: 25,
            currentPage: 0,
            filterValues: {},
            data: [],
            totalPages: 0,
            loading: false,
        };
        // this.fetchData = this.fetchData.bind(this);
        // this.dataProvider = props.dataProvider;
        this.prevPage = this.prevPage.bind(this);
        this.nextPage = this.nextPage.bind(this);
        this.onFilterChange = this.onFilterChange.bind(this);
        this.renderFilters = this.renderFilters.bind(this);
    }
    onFilterChange(e) {
        const targetValue = e.target.value;
        const targetName = e.target.name;
        const filterValues = Object.assign({ [targetName]: targetValue }, this.state.filterValues);
        this.setState({ loading: true });
        this.props.dataFetcher(filterValues, this.state.pageSize, this.state.currentPage).then(response => {
            const totalPages = Math.ceil(response.total / this.state.pageSize);
            const data = response.data;
            this.setState({ filterValues, data, totalPages, loading: false });
        });
    }
    prevPage() {
        if (this.state.currentPage > 0) {
            const currentPage = this.state.currentPage - 1;
            this.setState({ loading: true });
            this.props.dataFetcher(this.state.filterValues, this.state.pageSize, currentPage).then(response => {
                const totalPages = Math.ceil(response.total / this.state.pageSize);
                const data = response.data;
                this.setState({ data, totalPages, currentPage, loading: false });
            });
        }
    }
    nextPage() {
        const currentPage = this.state.currentPage + 1;
        this.setState({ loading: true });
        this.props.dataFetcher(this.state.filterValues, this.state.pageSize, currentPage).then(response => {
            const totalPages = Math.ceil(response.total / this.state.pageSize);
            const data = response.data;
            this.setState({ data, totalPages, currentPage, loading: false });
        });
    }
    render() {
        const Row = (props) => {
            const row = props.data;
            return (React.createElement("tr", null, this.props.columns.map(col => React.createElement("td", null, row[col.dataRowKey]))));
        };
        return (React.createElement("div", null,
            React.createElement("table", null,
                this.renderFilters(),
                this.state.data.map((rowData) => React.createElement(Row, { data: rowData }))),
            React.createElement("button", { onClick: this.prevPage }, "prev"),
            React.createElement("div", null, this.state.currentPage),
            React.createElement("button", { onClick: this.nextPage }, "next")));
    }
    renderFilters() {
        return this.props.columns.map(col => {
            if (col.filterType = 'text') {
                return React.createElement("td", null,
                    React.createElement("input", { name: col.filterName, type: "text", onChange: this.onFilterChange }));
            }
            if (col.filterType = 'date') {
                return React.createElement("td", null,
                    React.createElement("input", { name: col.filterName, type: "date", onChange: this.onFilterChange }));
            }
        });
    }
}
exports.AjaxTable = AjaxTable;
//# sourceMappingURL=AjaxTable.js.map