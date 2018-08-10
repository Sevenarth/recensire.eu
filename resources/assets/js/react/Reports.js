import React from 'react';
import Report from './Report';

export default class Reports extends React.Component {
    constructor(props) {
        super(props);

        const reports = [...props.reports];
        let ids = 0;
        reports.forEach((report, idx) => reports[idx].id = ++ids);

        this.state = {
            reports,
            status: undefined,
            changed: false,
            addReport: false,
            disabled: false,
            ids
        };
        window.onbeforeunload = () => {
            if(this.state.changed)
                return "Sono stati rilevati dei cambiamenti, continuare senza salvare?";
        }

        this.add = this.add.bind(this);
        this.update = this.update.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.resetChanges = this.resetChanges.bind(this);
    }

    add(report) {
        const reports = this.state.reports;
        report.id = this.state.ids+1;
        reports.push(report);
        
        this.setState({
            reports,
            addReport: false,
            changed: true,
            status: "Nuovo report aggiunto con successo!",
            ids: this.state.ids+1
        });
        setTimeout(() => this.setState({
            status: undefined
        }), 3000);
    }

    update(id) {
        return report => {
            let reports = this.state.reports;
            if(report) {
                reports[reports.findIndex(el => el.id === id)] = {...report, id};
            } else
                reports = reports.filter(el => el.id !== id);

            this.setState({
                reports,
                changed: true,
                status: `Report ${report ? 'aggiornato' : 'eliminato'} con successo!`
            });
            setTimeout(() => this.setState({
                status: undefined
            }), 3000);
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        if(!this.state.disabled) {
            this.setState({ disabled: true });
            axios.post(
                window.location.href,
                { reports: this.state.reports }
            ).then(({data}) => {
                this.setState({
                    disabled: false,
                    changed: false,
                    status: data.status
                });
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        }
    }

    resetChanges() {
        if(!this.state.disabled) {
            this.setState({
                reports: this.props.reports,
                status: undefined,
                changed: false,
                addReport: false
            });
        }
    }

    render() {
        return <div>
            {this.state.status && <div className="alert alert-success">
            {this.state.status}
            </div>}

            <button className="btn btn-info mb-3" type="button" onClick={evt => this.setState({addReport: true})}>Nuovo report</button>
            {this.state.addReport && <Report id={undefined} edit={true} cancel={() => this.setState({addReport:false})}changed={this.add} report={{}} />}
            {!this.state.reports.length && <p><i>Non ci sono ancora email report, creane una subito!</i></p>}
            <form onSubmit={this.handleSubmit}>
                {this.state.reports.map(report => <Report key={report.id} id={report.id} changed={this.update(report.id)} report={report} />)}
                {this.state.changed && <div className="btn-group">
                    <button disabled={this.state.disabled} className="btn btn-primary" type="submit">Salva cambiamenti</button>
                    <button disabled={this.state.disabled} onClick={this.resetChanges} type="button" data-placement="top" className="remove-confirmation btn btn-outline-info" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler annullare tutti i cambiamenti?">Annulla tutti i cambiamenti</button>
                </div>}
            </form>
        </div>;
    }
}