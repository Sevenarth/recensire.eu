import React from 'react';
import Shortcode from './Shortcode';

export default class Shortcodes extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            addShortcode: false,
            shortcodes: props.shortcodes
        };
        this.create = this.create.bind(this);
        this.save = this.save.bind(this);
        this.remove = this.remove.bind(this);
    }

    create(fields) {
        return axios.post(
            window.location.href,
            {create: true, ...fields}
        ).then(({data}) => {
            let sc = [
                ...this.state.shortcodes,
                data.shortcode
            ];
            sc.sort((a, b) => a.key > b.key);
            this.setState({
                status: data.status,
                addShortcode: false,
                shortcodes: sc
            });
            setTimeout(() => this.setState({
                status: undefined
            }), 3000);
        }).catch(err => Promise.reject(err));
    }

    save(id) {
        return fields => {
            return axios.post(
                window.location.href,
                fields
            ).then(({data}) => {
                const sc = this.state.shortcodes;
                sc[sc.findIndex(el => el.id === id)] = data.shortcode;
                this.setState({
                    status: data.status,
                    shortcodes: sc
                });
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        };
    }

    remove(id) {
        return key => {
            return axios.post(
                window.location.href,
                {key, delete: true}
            ).then(({data}) => {
                const sc = this.state.shortcodes.filter(el => el.id !== id);
                this.setState({
                    status: data.status,
                    shortcodes: sc
                });
                setTimeout(() => this.setState({
                    status: undefined
                }), 3000);
            }).catch(err => Promise.reject(err));
        };
    }

    render() {
        return <div>
          {this.state.status && <div className="alert alert-success">
            {this.state.status}
          </div>}

            <button className="btn btn-primary mb-3" onClick={evt => this.setState({addShortcode: true})}>Nuovo shortcode</button>

            <ul className="list-group">
                <li className="list-group-item list-group-item-primary">
                    <div className="row">
                        <div className="col-sm-3"><b>Shortcode</b></div>
                        <div className="col-sm-9"><b>Valore</b></div>
                    </div>
                </li>
                {this.state.addShortcode && <Shortcode
                    close={() => this.setState({addShortcode: false}) } 
                    save={this.create}
                    shortcode={{}}
                    edit={true}
                />}
                {this.state.shortcodes.map((shortcode) => <Shortcode
                    close={this.remove(shortcode.id)}
                    save={this.save(shortcode.id)}
                    key={shortcode.id}
                    shortcode={shortcode}
                />)}
            </ul>
        </div>;
    }
}