import React from 'react';
import Query from './Query';
import SpecialMDE from '../specialmde';

export default class Report extends React.Component {
    constructor(props) {
        super(props);

        const queries = [...props.queries];
        let ids = 0;
        queries.forEach((query, idx) => queries[idx].id = ++ids);

        this.state = {
            id: props.id,
            title: props.title || "",
            subject: props.subject || "",
            preface: props.preface || "",
            postface: props.postface || "",
            queries,
            status: undefined,
            changed: false,
            addQuery: false,
            disabled: false,
            edit: props.edit || false,
            ids,
            errors: {}
        };
        window.onbeforeunload = () => {
            if(this.state.changed)
                return "Sono stati rilevati dei cambiamenti, continuare senza salvare?";
        }

        this.add = this.add.bind(this);
        this.update = this.update.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.resetChanges = this.resetChanges.bind(this);
        this.applyCodes = this.applyCodes.bind(this);
        this.delete = this.delete.bind(this);
    }

    componentWillUpdate(prevProps) {
        if(prevProps.id !== this.state.id) {
            const queries = [...this.props.queries];
            let ids = 0;
            queries.forEach((query, idx) => queries[idx].id = ++ids);

            this.setState({
                id: this.props.id,
                title: this.props.title || "",
                subject: this.props.subject || "",
                preface: this.props.preface || "",
                postface: this.props.postface || "",
                queries,
                status: undefined,
                changed: false,
                addQuery: false,
                disabled: false,
                edit: this.props.edit || false,
                ids,
                errors: {}
            });
        }
    }

    add(query) {
        const queries = this.state.queries;
        query.id = this.state.ids+1;
        queries.push(query);
        
        this.setState({
            queries,
            addQuery: false,
            changed: true,
            status: "Nuova query aggiunta con successo!",
            ids: this.state.ids+1
        });
        setTimeout(() => this.setState({
            status: undefined
        }), 3000);
    }

    update(id) {
        return query => {
            let queries = this.state.queries;
            if(query) {
                queries[queries.findIndex(el => el.id === id)] = {...query, id};
            } else
                queries = queries.filter(el => el.id !== id);

            this.setState({
                queries,
                changed: true,
                status: `Query ${query ? 'aggiornata' : 'eliminata'} con successo!`
            });
            setTimeout(() => this.setState({
                status: undefined
            }), 3000);
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        if(!this.state.disabled) {
            if(!this.state.title.trim().length) {
                this.setState({ errors: { title: "Inserisci un titolo valido!" } });
                return;
            }

            if(!this.state.subject.trim().length) {
                this.setState({ errors: { subject: "Inserisci un subject valido!" } });
                return;
            }

            if(!this.state.queries.length) {
                this.setState({ errors: { queries: "Inserisci almeno una query!" } });
                return;
            }

            this.setState({ disabled: true });
            axios.post(
                window.location.href,
                { 
                    id: this.state.id,
                    title: this.state.title,
                    subject: this.state.subject,
                    preface: this.state.preface,
                    postface: this.state.postface,
                    queries: this.state.queries
                }
            ).then(({data}) => {
                this.setState({
                    id: data.report.id,
                    edit: false,
                    disabled: false,
                    changed: false,
                    status: data.status,
                    errors: {}
                });
                this.props.update(data.report);
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        }
    }

    delete() {
        if(!this.state.disabled) {
            this.setState({ disabled: true });
            axios.post(
                window.location.href,
                { 
                    id: this.state.id,
                    delete: true
                }
            ).then(({data}) => {
                const id = this.state.id;
                this.setState({
                    edit: false,
                    disabled: false,
                    changed: false,
                    status: data.status,
                    errors: {}
                });

                this.props.delete(id);
                
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        }
    }

    resetChanges() {
        if(!this.state.disabled) {
            const queries = [...this.props.queries];
            let ids = 0;
            queries.forEach((query, idx) => queries[idx].id = ++ids);

            this.setState({
                id: this.props.id,
                title: this.props.title || "",
                subject: this.props.subject || "",
                preface: this.props.preface || "",
                postface: this.props.postface || "",
                queries: queries,
                status: undefined,
                changed: false,
                addQuery: false,
                edit: false,
                errors: {}
            });
        }
    }

    applyCodes(_text, mdEnabled = true) {
        var text = _text;
        text = text.replace(/\%date/gmi, mdEnabled ? "**Date**" : "DATE");
        if(window.reportsEntity.id) {
            text = text.replace(/\%store/gmi, mdEnabled ? `**${window.reportsEntity.name}**` : window.reportsEntity.name);
            text = text.replace(/\%seller/gmi, mdEnabled ? `**${window.reportsEntity.seller.nickname}**` : window.reportsEntity.seller.nickname);
        } else {
            text = text.replace(/\%store/gmi, mdEnabled ? '**Store**' : 'STORE');
            text = text.replace(/\%seller/gmi, mdEnabled ? '**Seller**' : 'SELLER');
        }

        window.shortcodes.forEach(sc => {
            text = text.replace(new RegExp(`#${RegExp.quote(sc.key)}(?![a-zA-Z0-9\-])`, 'gm'), sc.value);
        });

        return text;
    }

    render() {
        return <div>
            {this.state.status && <div className="alert alert-success">
            {this.state.status}
            </div>}
            <form onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <label>Titolo report</label>
                    {this.state.edit ?
                    <input type="text" className="form-control" value={this.state.title} onChange={e => this.setState({title: e.target.value})} />
                    : <div className="form-control">{this.state.title}</div>}
                    {this.state.errors.title && <div className="invalid-feedback d-block">{this.state.errors.title}</div>}
                </div>

                <div className="form-group">
                    <label>Subject email</label>
                    {this.state.edit ?
                    <input type="text" className="form-control" value={this.state.subject} onChange={e => this.setState({subject: e.target.value})} />
                    : <div className="form-control">{this.applyCodes(this.state.subject, false)}</div>}
                    {this.state.errors.subject && <div className="invalid-feedback d-block">{this.state.errors.subject}</div>}
                </div>

                <div className="form-group">
                    <label>Header</label>
                    {this.state.edit ?
                    <SpecialMDE value={this.state.preface} onChange={preface => this.setState({preface})} />
                    : <div className="maxScroll form-control" dangerouslySetInnerHTML={{__html: MD.render(this.applyCodes(this.state.preface))}} />}
                </div>
            </form>

            <button className="btn btn-info mb-3" type="button" onClick={evt => this.setState({addQuery: true})}>Nuova query</button>
            {this.state.addQuery && <Query id={undefined} edit={true} cancel={() => this.setState({addQuery:false})}changed={this.add} report={{}} />}
            {!this.state.queries.length && <p><i>Non ci sono ancora queries, creane una subito!</i></p>}
            <form onSubmit={this.handleSubmit}>
                {this.state.queries.map(query => <Query hasChanged={this.state.changed} key={query.id} id={query.id} changed={this.update(query.id)} report={query} />)}
                {this.state.errors.queries && <div className="invalid-feedback d-block">{this.state.errors.queries}</div>}

                <div className="form-group">
                    <label>Footer</label>
                    {this.state.edit ?
                    <SpecialMDE value={this.state.postface} onChange={postface => this.setState({postface})} />
                    : <div className="maxScroll form-control" dangerouslySetInnerHTML={{__html: MD.render(this.applyCodes(this.state.postface))}} />}
                </div>

                {(this.state.changed || this.state.edit) && <div className="btn-group w-100 mb-3">
                    <button disabled={this.state.disabled} className="btn btn-primary" type="submit">Salva cambiamenti</button>
                    <button disabled={this.state.disabled} onClick={this.resetChanges} type="button" data-placement="top" className="remove-confirmation btn btn-outline-info" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler annullare tutti i cambiamenti?">Annulla tutti i cambiamenti</button>
                </div>}

                {!this.state.edit && <div className="btn-group">
                    <button disabled={this.state.disabled} className="btn btn-primary" onClick={e => this.setState({edit:true})} type="button">Modifica campi</button>
                    <button disabled={this.state.disabled} onClick={this.delete} type="button" data-placement="top" className="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare il report corrente?"><i className="fa fa-fw fa-times"></i> Elimina</button>
                </div>}
            </form>
        </div>;
    }
}