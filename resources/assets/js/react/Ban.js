import React from 'react';
import SpecialMDE from '../specialmde';

export default class Ban extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: props.ban.id,
            email: props.ban.email || "",
            notes: props.ban.notes || "",
            created_at: props.ban.created_at || "",
            edit: props.edit || false,
            disabled: undefined,
            errors: {}
        };
        this.submit = this.submit.bind(this);
        this.close = this.close.bind(this);
        this.closeAndRollback = this.closeAndRollback.bind(this);
        this.applyCodes = this.applyCodes.bind(this);
    }

    submit(e) {
        e.preventDefault();
        if(!this.state.disabled) {
            this.setState({disabled: true});
            if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(this.state.email))
                this.setState({
                    errors: {
                        email: "Inserire una email valida"
                    },
                    disabled: undefined
                });
            else
                this.props.save({
                    email: this.state.email,
                    notes: this.state.notes
                }).then(() => this.state.id && this.setState({edit: false, disabled: undefined}))
                .catch(err => this.setState({
                    errors: err.response.data,
                    disabled: undefined
                }));
        }
    }

    close(e) {
        if(!this.state.disabled) {
            this.setState({disabled: true});
            this.props.close(this.state.email);
        }
    }

    closeAndRollback() {
        this.setState({
            edit: false,
            email: this.props.ban.email,
            notes: this.props.ban.notes
        });
    }

    applyCodes(_text) {
        var text = _text;

        window.shortcodes.forEach(sc => {
            text = text.replace(new RegExp(`#${RegExp.quote(sc.key)}(?![a-zA-Z0-9\-])`, 'gm'), sc.value);
        });

        return text;
    }

    render() {
        return this.state.edit ? <li
            id={"ban-" + (this.state.id || "new")}
            className="list-group-item">
            <form onSubmit={this.submit}>
            <div className="row">
            <div className="col-sm-3 break-word">
                {this.state.id ? <b>{this.state.email}</b> : <div className="input-group input-group-sm">
                    <input type="email" className={"form-control" + (this.state.errors.shortcode ? ' is-invalid':'')} value={this.state.email} onChange={evt => this.setState({
                        email: evt.target.value
                    })} placeholder="me@example.com" />
                    {this.state.errors.email && <div className="invalid-feedback d-block">{this.state.errors.email}</div>}
                </div>}
            </div>
            <div className="col-sm-9">
                <SpecialMDE value={this.state.notes} onChange={value => this.setState({ notes: value }) } />
                <div className="mt-3">
                    <button disabled={this.state.disabled} type="submit" className="btn btn-primary mr-2">{this.state.id ? 'Salva' : 'Aggiungi'}</button>
                    <button disabled={this.state.disabled} onClick={e => (this.state.id ? this.closeAndRollback() : this.close())} type="button" className="btn btn-outline-info mr-2">Chiudi</button>
                    {this.state.id && <button type="button" disabled={this.state.disabled} onClick={this.close} data-placement="top" className="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo ban?"><i className="fa fa-fw fa-times"></i> Elimina</button>}
                </div>
            </div>
            </div>
            </form>
            </li>
        : <a onClick={evt => this.setState({edit:true})}
            href={"#ban-" + (this.state.id || "new")}
            id={"ban-" + (this.state.id || "new")}
            className="list-group-item list-group-item-action"
        ><div className="row">
        <div className="col-sm-3 break-word"><b>{this.state.email}</b>
            </div><div className="col-sm-4">
                {this.state.id ? this.state.created_at : '-'}
            </div>
            <div className="col-sm-5 maxScroll" dangerouslySetInnerHTML={{ __html: MD.render(this.applyCodes(this.state.notes))}} />
        </div></a>;
    }
}