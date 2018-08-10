import React from 'react';
import SpecialMDE from '../specialmde';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import _ from 'lodash';

RegExp.quote = function(str) {
    return (str+'').replace(/[.?*+^$[\]\\(){}|-]/g, "\\$&");
};

export default class Report extends React.Component {
    constructor(props) {
        super(props);
        const fields = props.report.fields || [];

        this.state = {
            subject: props.report.subject || "",
            preface: props.report.preface || "",
            postface: props.report.postface || "",
            fields: {
                report: fields,
                available: _.difference(Object.keys(window.reportsFields), fields)
            },
            statusType: props.report.statusType || "all",
            statuses: props.report.statuses || [],
            onlyCurrent: props.report.onlyCurrent || false,
            orderBy: props.report.orderBy || {},
            edit: props.edit || false,
            errors: {}
        }
        
        this.change = this.change.bind(this);
        this.save = this.save.bind(this);
        this.cancel = this.cancel.bind(this);
        this.remove = this.remove.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);
        this.handleStatusTypeChange = this.handleStatusTypeChange.bind(this);
        this.handleMultipleSelectChange = this.handleMultipleSelectChange.bind(this);
    }

    change(key, eventful = true) {
        return data => this.setState({
            [key]: eventful ? data.target.value : data
        });
    }

    save() {
        if(!this.state.subject.trim().length) {
            this.setState({
                errors: {
                    subject: "Questo campo è obbligatorio"
                }
            });
            return;
        }
        if(!this.state.fields.report.length) {
            this.setState({
                errors: {
                    fields: "Bisogna inserire almeno un campo!"
                }
            });
            return;
        }

        this.setState({
            edit: false,
            errors: {}
        });
        this.props.changed({
            subject: this.state.subject.trim(),
            preface: this.state.preface.trim(),
            fields: this.state.fields.report,
            postface: this.state.postface.trim(),
            statusType: this.state.statusType,
            orderBy: this.state.orderBy,
            statuses: this.state.statuses,
            onlyCurrent: this.state.onlyCurrent,
        });
        this.container.scrollIntoView({ behavior: 'smooth' });
    }

    cancel() {
        this.container.scrollIntoView({ behavior: 'smooth' });
        if(this.props.cancel)
            this.props.cancel();
        else {
            const fields = this.props.report.fields || [];
            this.setState({
                edit: false,
                subject: this.props.report.subject || "",
                preface: this.props.report.preface || "",
                fields: {
                    report: fields,
                    available: _.difference(Object.keys(window.reportsFields), fields)
                },
                statusType: this.props.report.statusType || "all",
                statuses: this.props.report.statuses || [],
                onlyCurrent: this.props.report.onlyCurrent || false,
                postface: this.props.report.postface || "",
                orderBy: this.props.report.orderBy || {},
                errors: {}
            });
        }
    }

    remove() {
        this.props.changed();
    }

    onDragEnd(result){
        // Apply changes
        const { destination, source, draggableId } = result;

        if(!destination) return;

        if(
            destination.droppableId === source.droppableId &&
            destination.index === source.index
        ) return;

        const start = this.state.fields[source.droppableId];
        const finish = this.state.fields[destination.droppableId];

        if(start === finish) {
            const reordered = Array.from(start);
            reordered.splice(source.index, 1);
            reordered.splice(destination.index, 0, draggableId);

            this.setState({
                fields: {
                    ...this.state.fields,
                    [source.droppableId]: reordered
                }
            });
            
            return;
        }

        const startReordered = Array.from(start);
        startReordered.splice(source.index, 1);
        const finishReordered = Array.from(finish);
        finishReordered.splice(destination.index, 0, draggableId);

        this.setState({
            fields: {
                ...this.state.fields,
                [source.droppableId]: startReordered,
                [destination.droppableId]: finishReordered
            }
        });
    }

    handleStatusTypeChange(e) {
        this.setState({
          statusType: e.target.value
        });
    }

    handleMultipleSelectChange(e) {
        this.setState({
            statuses: [...e.target.options].filter(({selected}) => selected).map(({value}) => value)
        });
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
        const Blank = props => props.children;

        return <div ref={ref => this.container = ref} className="card mb-4">
            <h5 className="card-header">
                {this.state.edit ?
                    <input type="text" className="form-control" placeholder="Oggetto" value={this.state.subject} onChange={this.change('subject')} />
                    : this.applyCodes(this.state.subject, false)}
                {this.state.errors.subject && <div className="invalid-feedback d-block">{this.state.errors.subject}</div>}
            </h5>
            <div className="card-body">
                {this.state.edit && <h5 className="card-title">Prefazione</h5>}
                {this.state.edit ?
                    <SpecialMDE value={this.state.preface} onChange={this.change('preface', false)} />
                    : (this.state.preface && <div className="card-text maxScroll" dangerouslySetInnerHTML={{__html: MD.render(this.applyCodes(this.state.preface))}} />)}
                {(this.state.preface || this.state.edit) && <hr />}
                {this.state.edit && <h5 className="card-title">Tabella</h5>}
                {this.state.edit ? <div className="row">
                    <div className="col-sm-3">
                        <div className="custom-control custom-radio">
                            <input type="radio" className="custom-control-input" name="status" value="all" id="status_all" onChange={this.handleStatusTypeChange} checked={this.state.statusType === "all"} />
                            <label className="custom-control-label" htmlFor="status_all">Tutti</label>
                        </div>
                    </div>
                    <div className="col-sm-3">
                        <div className="custom-control custom-radio">
                            <input type="radio" className="custom-control-input" name="status" value="expiring" id="status_expiring" onChange={this.handleStatusTypeChange} checked={this.state.statusType === "expiring"} />
                            <label className="custom-control-label" htmlFor="status_expiring">In scadenza/Scaduti</label>
                        </div>
                    </div>
                    <div className="col-sm-6">
                        <div className="custom-control custom-radio">
                            <input type="radio" className="custom-control-input" name="status" value="others" id="status_others" onChange={this.handleStatusTypeChange} checked={this.state.statusType === "others"} />
                            <label className="custom-control-label w-100" htmlFor="status_others">
                                <select size={window.statuses.length} value={this.state.statuses} onClick={e => this.setState({statusType: 'others'})} onChange={this.handleMultipleSelectChange} className="custom-select" multiple>
                                    {window.statuses.map(status => <option key={status.value} value={status.value}>{status.display}</option>)}
                                </select>
                            </label>
                        </div>
                    </div>
                </div> : <p><b>Stati delle unità di test</b>: {this.state.statusType == "all" ? "Tutti" : (this.state.statusType == "expiring" ? "In scadenza/Scaduti" : this.state.statuses.map(status => window.statuses.filter(el => el.value == status)[0].display).join(", "))}</p>}
                {this.state.edit ? <div className="row">
                    <div className="col-sm-6">
                        <div className="custom-control custom-checkbox">
                            <input type="checkbox" className="custom-control-input" checked={this.state.onlyCurrent} onChange={e => this.setState({onlyCurrent: !this.state.onlyCurrent})} id="current_state" />
                            <label className="custom-control-label" htmlFor="current_state">Solo stato corrente</label>
                        </div>
                    </div>
                </div> : <p><b>Mostra solo stato corrente?</b> {this.state.onlyCurrent ? 'Sì' : 'No'}</p>}
                {this.state.edit ? <DragDropContext onDragEnd={this.onDragEnd}>
                    <label className="mt-3">Campi disponibili</label>
                    <Droppable droppableId="available" direction="horizontal">
                        {(provided, snapshot) => <div ref={provided.innerRef} style={snapshot.isDraggingOver ? {backgroundColor: '#ffe8be'} : {}} className="fields-row" {...provided.droppableProps}>
                            {this.state.fields.available.map((field, idx) => <Blank key={field}>
                                <Draggable draggableId={field} index={idx}>
                                    {(provided_, snapshot_) => <div {...provided_.draggableProps} {...provided_.dragHandleProps} ref={provided_.innerRef} className={"field-column" + (snapshot_.isDragging ? ' dragging' : '')}>
                                        {window.reportsFields[field]}
                                    </div>}
                                </Draggable>
                            </Blank>)}
                            {provided.placeholder}
                        </div>}
                    </Droppable>
                    <label className="mt-3">Colonne in tabella</label>
                    <Droppable droppableId="report" direction="horizontal">
                        {(provided, snapshot) => <div ref={provided.innerRef} style={snapshot.isDraggingOver ? {backgroundColor: '#ffe8be'} : {}} className="fields-row" {...provided.droppableProps}>
                            {this.state.fields.report.map((field, idx) => <Blank key={field}>
                                <Draggable draggableId={field} index={idx}>
                                    {(provided_, snapshot_) => <div {...provided_.draggableProps} {...provided_.dragHandleProps} ref={provided_.innerRef} className={"field-column" + (snapshot_.isDragging ? ' dragging' : '')}>
                                        {window.reportsFields[field]}
                                    </div>}
                                </Draggable>
                            </Blank>)}
                            {provided.placeholder}
                        </div>}
                    </Droppable>
                </DragDropContext> : <div className="form-group"><label>Colonne in tabella</label><div className="fields-row">
                    {this.state.fields.report.map(field => <div className="field-column" key={field}>{window.reportsFields[field]}</div>)}
                </div></div>}
                {this.state.errors.fields && <div className="invalid-feedback d-block">{this.state.errors.fields}</div>}
                <label className="mt-3">Ordina per</label>
                {this.state.edit ? <div className="row">
                    <div className="col-sm-4">
                    <select className="custom-select" value={this.state.orderBy.field} onChange={e => this.setState({orderBy: {...this.state.orderBy, field: e.target.value}})}>
                        <option>-- nessun ordine --</option>
                        {Object.keys(window.reportsFields).map(field => <option value={field} key={field}>{window.reportsFields[field]}</option>)}
                    </select>
                    </div>
                    <div className="col-sm-3">
                    <select className="custom-select" value={this.state.orderBy.type} onChange={e => this.setState({orderBy: {...this.state.orderBy, type: e.target.value}})}>
                        <option value="asc">Ascendente</option>
                        <option value="desc">Discendente</option>
                    </select>
                    </div>
                </div> : <p className="form-control">{this.state.orderBy.field ? (window.reportsFields[this.state.orderBy.field] + " • " + (this.state.orderBy.type == "desc" ? "Discendente" : "Ascendente")) : '-'}</p>}
                {(this.state.postface || this.state.edit) && <hr />}
                {this.state.edit && <h5 className="card-title">Postfazione</h5>}
                {this.state.edit ?
                    <SpecialMDE value={this.state.postface} onChange={this.change('postface', false)} />
                    : (this.state.postface && <div className="card-text maxScroll" dangerouslySetInnerHTML={{__html: MD.render(this.applyCodes(this.state.postface))}} />)}
            </div>    
            <div className="card-footer">
                {this.state.edit ? <div className="btn-group">
                    <button type="button" className="btn btn-primary" onClick={this.save}>{this.props.id ? 'Salva' : 'Aggiungi'}</button>
                    <button type="button" className="btn btn-outline-info" onClick={this.cancel}>Annulla</button>
                </div> : <div className="btn-group">
                    <button type="button" className="btn btn-primary" onClick={e => this.setState({edit: true})}>Modifica</button>
                    <button type="button" onClick={this.remove} data-placement="top" className="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa email report?"><i className="fa fa-fw fa-times"></i> Elimina</button>
                </div>}
            </div>
        </div>;
    }
}