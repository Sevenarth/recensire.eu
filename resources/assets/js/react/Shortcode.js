import React from 'react';
import SpecialMDE from '../specialmde';

export default class Shortcode extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: props.shortcode.id,
            shortcode: props.shortcode.key || "",
            value: props.shortcode.value || "",
            edit: props.edit || false,
            disabled: undefined,
            errors: {}
        };
        this.submit = this.submit.bind(this);
        this.close = this.close.bind(this);
        this.closeAndRollback = this.closeAndRollback.bind(this);
    }

    submit(e) {
        e.preventDefault();
        if(!this.state.disabled) {
            this.setState({disabled: true});
            if(!/^([0-9a-z\-]+)$/i.test(this.state.shortcode))
                this.setState({
                    errors: {
                        shortcode: "Inserire uno shortcode valido"
                    },
                    disabled: undefined
                });
            else
                this.props.save({
                    key: this.state.shortcode,
                    value: this.state.value
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
            this.props.close(this.state.shortcode);
        }
    }

    closeAndRollback() {
        this.setState({
            edit: false,
            key: this.props.shortcode.key,
            value: this.props.shortcode.value
        });
    }

    render() {
        return this.state.edit ? <li
            id={"shortcode-" + (this.state.id || "new")}
            className="list-group-item">
            <form onSubmit={this.submit}>
            <div className="row">
            <div className="col-sm-3">
                {this.state.id ? <b>#{this.state.shortcode}</b> : <div className="input-group input-group-sm">
                    <div className="input-group-prepend">
                        <span className="input-group-text">#</span>
                    </div>
                    <input type="text" className={"form-control" + (this.state.errors.shortcode ? ' is-invalid':'')} value={this.state.shortcode} onChange={evt => this.setState({
                        shortcode: evt.target.value
                    })} placeholder="shortcode" />
                    {this.state.errors.shortcode && <div className="invalid-feedback">{this.state.errors.shortcode}</div>}
                </div>}
            </div>
            <div className="col-sm-9">
                <SpecialMDE value={this.state.value} onChange={value => this.setState({ value }) } />
                <div className="mt-3">
                    <button disabled={this.state.disabled} type="submit" className="btn btn-primary mr-2">{this.state.id ? 'Salva' : 'Aggiungi'}</button>
                    <button disabled={this.state.disabled} onClick={e => (this.state.id ? this.closeAndRollback() : this.close())} type="button" className="btn btn-outline-info mr-2">Chiudi</button>
                    {this.state.id && <button type="button" disabled={this.state.disabled} onClick={this.close} data-placement="top" className="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo shortcode?"><i className="fa fa-fw fa-times"></i> Elimina</button>}
                </div>
            </div>
            </div>
            </form>
            </li>
        : <a onClick={evt => this.setState({edit:true})}
            href={"#shortcode-" + (this.state.id || "new")}
            id={"shortcode-" + (this.state.id || "new")}
            className="list-group-item list-group-item-action"
        ><div className="row">
        <div className="col-sm-3"><b>#{this.state.shortcode}</b>
            </div>
            <div className="col-sm-9 maxScroll" dangerouslySetInnerHTML={{ __html: MD.render(this.state.value)}} />
        </div></a>;
    }
}