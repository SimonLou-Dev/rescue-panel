import React from 'react';
import {Link} from "react-router-dom";
import axios from "axios";


class GetInfos extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            live: 1,
            tel: "",
            compte: "",
            errors: []
        }
        this.sendinfos = this.sendinfos.bind(this)
    }

    async sendinfos(e) {
        e.preventDefault();
        await axios({
            method: 'post',
            url: '/data/postuserinfos',
            data: {
                'living': this.state.live,
                'tel': this.state.tel,
                'compte': this.state.compte,
                'X-CSRF-TOKEN': csrf,
            }
        }).then(response => {
            if (response.status === 201) {
                window.location.href = "/";
            }
        }).catch(error => {
            error = Object.assign({}, error);
            if(error.response.status === 422){
                this.setState({errors: error.response.data.errors})
            }
        })

    }



    render() {
        return (
            <div className={'Register'}>
                <div className={'Form'}>
                    <form method={'POST'} onSubmit={this.sendinfos}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                        <h1>Informations</h1>
                        <label>Contré habité : </label>
                        <select defaultValue={this.state.live} onChange={(e)=>this.setState({live:e.target.value}) }>
                            <option value={1} disabled>choisir</option>
                            <option>LS</option>
                            <option>BC</option>
                        </select>

                        <label>n° de tel IG : </label>
                        <input type={'number'} value={this.state.tel} className={(this.state.errors.tel ? 'form-error': '')} onChange={(e)=>{this.setState({tel:e.target.value}); this.componentDidMount()}}/>
                        <ul className={'error-list'}>
                            {this.state.errors.tel && this.state.errors.tel.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>
                        <label>n° de compte </label>
                        <input type={'number'} value={this.state.compte} className={(this.state.errors.compte ? 'form-error': '')} name={'psw'} onChange={(e)=>{this.setState({compte:e.target.value}); this.componentDidMount()}}/>
                        <ul className={'error-list'}>
                            {this.state.errors.compte && this.state.errors.compte.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>
                        <div className={'btn-contain'}>
                            <button type={'submit'} className={'btn'}>Terminer</button>
                        </div>
                    </form>
                </div>
            </div>
        )
    };
}

export default GetInfos;
