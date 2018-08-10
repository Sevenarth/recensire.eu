import React from 'react';
import Ban from './Ban';

export default class BanList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            addBan: false,
            banlist: props.banlist,
            search: ""
        };
        this.create = this.create.bind(this);
        this.save = this.save.bind(this);
        this.remove = this.remove.bind(this);
        this.onFileChange = this.onFileChange.bind(this);
    }

    create(fields, message = true) {
        return axios.post(
            window.location.href,
            {create: true, ...fields}
        ).then(({data}) => {
            let bl = [...this.state.banlist];
            if(data.ban)
                bl.push(data.ban);
            else if(data.bans)
                bl = bl.concat(data.bans);

            bl.sort((a, b) => a.created_at < b.created_at);
            this.setState({
                status: message ? data.status : undefined,
                addBan: false,
                banlist: bl
            });
            if(message)
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            else
                return Promise.resolve(data);
        }).catch(err => Promise.reject(err));
    }

    save(id) {
        return fields => {
            return axios.post(
                window.location.href,
                fields
            ).then(({data}) => {
                const bl = this.state.banlist;
                bl[bl.findIndex(el => el.id === id)] = data.ban;
                this.setState({
                    status: data.status,
                    banlist: bl
                });
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        };
    }

    remove(id) {
        return email => {
            return axios.post(
                window.location.href,
                {email, delete: true}
            ).then(({data}) => {
                const bl = this.state.banlist.filter(el => el.id !== id);
                this.setState({
                    status: data.status,
                    banlist: bl
                });
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        };
    }

    onFileChange(e) {
        let f = e.target.files[0];

        if(!f) return;

        if (!f.type.match('text/csv'))
            return;

        var reader = new FileReader();
        reader.onloadend = e => {
            if (e.target.readyState == FileReader.DONE) {
                var lines = e.target.result.match(/[^\r\n]+/g);
                var k = 0, mass = [];

                lines.forEach(line => {
                    let entry = line.split(/,(.+)?/),
                        email = entry[0].trim(),
                        notes = entry.length > 1 && entry[1] ? entry[1].trim() : '';
                    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(email))
                        return;
                    
                    if(/^("|').*("|')$/.test(notes))
                        notes = notes.substr(1, notes.length-2);

                    mass.push({email, notes});
                    k++;
                });

                if(k > 0)
                    this.create({mass}, false).then(data => {
                        this.setState({ status: `Sono state importate ${data.count} su ${k} emails valide con successo!`});

                        setTimeout(() => this.setState({
                            status: undefined
                        }), 3000);
                    }).catch(err => {});
            }
        };
        reader.readAsText(f);
    }

    render() {
        return <div>
          {this.state.status && <div className="alert alert-success">
            {this.state.status}
          </div>}

            <input type="file" id="file" ref={ref => this.uploader = ref} className="d-none" onChange={this.onFileChange} accept=".csv" />
            <button className="btn btn-info float-right" onClick={e => this.uploader.click()}><i className="fa fa-fw fa-upload"></i> Importa</button>
            <button className="btn btn-primary mb-3" onClick={evt => this.setState({addBan: true})}>Nuovo ban</button>

            <div className="row">
                <div className="col-sm-6">
                <div className="input-group mb-3">
                    <input type="text" className="form-control" placeholder="Cerca qui..." value={this.state.search} onChange={e => this.setState({search: e.target.value.trim()})} />
                    {this.state.search.length > 0 && <div className="input-group-append">
                        <button type="button" className="btn btn-info" onClick={() => this.setState({search: ""})}>Reset</button>
                    </div>}
                    </div>
                </div>
            </div>

            <ul className="list-group">
                <li className="list-group-item list-group-item-primary">
                    <div className="row">
                        <div className="col-sm-3"><b>Email</b></div>
                        <div className="col-sm-4"><b>Data</b></div>
                        <div className="col-sm-5"><b>Note</b></div>
                    </div>
                </li>
                {this.state.addBan && <Ban
                    close={() => this.setState({addBan: false}) } 
                    save={this.create}
                    ban={{}}
                    edit={true}
                />}
                {this.state.banlist.filter(ban => {
                    if(this.state.search.length > 0) {
                        return (ban.email && ban.email.indexOf(this.state.search) !== -1) ||
                            (ban.notes && ban.notes.indexOf(this.state.search) !== -1);
                    } else
                        return true;
                }).map((ban) => <Ban
                    close={this.remove(ban.id)}
                    save={this.save(ban.id)}
                    key={ban.id}
                    ban={ban}
                />)}
            </ul>
        </div>;
    }
}