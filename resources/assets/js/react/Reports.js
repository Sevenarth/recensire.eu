import React from 'react';
import Report from './Report';

export default class Reports extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            reports: props.reports,
            selected: props.reports.length > 0 ? props.reports[0].id : undefined,
            addReport: false
        };
        this.create = this.create.bind(this);
        this.update = this.update.bind(this);
        this.delete = this.delete.bind(this);
    }

    create() {
        if(this.state.reports.findIndex(el => el.id == "new") === -1) {
            const reports = this.state.reports;
            reports.push({id: "new", queries: []});
            this.setState({
                reports,
                selected: "new"
            });
        } else
            this.setState({
                selected: "new"
            });
    }

    delete(id) {
        let idx = this.state.reports.findIndex(el => el.id === id);
        if(idx >= 0) {
            let reports = [...this.state.reports];
            reports.splice(idx, 1);
            this.setState({
                reports,
                selected: undefined
            })
        }

    }

    update(id) {
        return report => {
            const reports = this.state.reports;
            reports[reports.findIndex(el => el.id === id)] = report;
            this.setState({
                selected: report.id,
                reports
            });
        }
    }

    render() {
        let index = this.state.reports.findIndex(el => el.id === this.state.selected);
        if(index === -1) index = this.state.reports.length > 0 ? 0 : undefined;

        return <div>
            {this.state.reports.length > 0 ? <div>
                <label>Reports disponibili: </label><div className="row">
                <div className="col-sm-6 input-group">
                <select value={this.state.reports[index].id} onChange={e => this.setState({selected: e.target.value == "new" ? "new" : parseInt(e.target.value)})} className="custom-select">
                    {this.state.reports.map(report => <option key={report.id} value={report.id}>{report.title || "-- nuovo report --"}</option>)}
                </select>
                <button className="btn btn-primary" onClick={this.create}>Crea nuovo report</button></div></div>
            </div> : <div>
                Per iniziare: <button className="btn btn-primary" onClick={this.create}>Crea nuovo report</button>
            </div>}

            {this.state.reports[index] && <div><hr /><Report
                id={this.state.reports[index].id}
                title={this.state.reports[index].title}
                subject={this.state.reports[index].subject}
                preface={this.state.reports[index].preface}
                postface={this.state.reports[index].postface}
                queries={this.state.reports[index].queries}
                edit={this.state.reports[index].id == "new"}
                update={this.update(this.state.reports[index].id)}
                delete={this.delete}
            /></div>}
        </div>
    }
};