import React from 'react';
import axios from "axios";
import PermsContext from "../../../context/PermsContext";
import {Link} from "react-router-dom";

class PersonnelLine extends React.Component {
    constructor(props) {
        super(props);
        this.state = {id: this.props.id, name: this.props.name, grade: this.props.grade, discordid: this.props.discordid}
        this.isupdate = this.isupdate.bind(this);
    }

    async isupdate(e) {
        e.preventDefault();
        await axios({
            url: '/data/users/setgrade/' + this.state.grade + '/' + this.state.id,
            method: 'POST',
        })
        this.props.update();

    }

    render() {
        let perm = this.context;
        return (
            <tr>
                <td className={'id'}>{this.state.id}</td>
                <td className={'name'}>
                    {perm.view_member_sheet === 1 &&
                    <Link to={'/gestion/Fiches?id='+ this.state.id}>{this.state.name}</Link>
                    }

                   {perm.view_member_sheet === 0 &&
                    this.state.name
                   }
                    </td>
                <td className={'matricule'}>{this.props.matricule}</td>
                <td className={'tel'}>{this.props.tel}</td>
                <td className={'compte'}>{this.props.compte}</td>
                <td className={'discordId'}>
                    <form onSubmit={async (e) => {
                        e.preventDefault();
                        await axios({
                            method: 'PUT',
                            url: '/data/users/setdiscordId/' + this.state.discordid + '/' + this.state.id,
                        })
                        this.props.update();
                    }}>
                        <input type={'number'} disabled={(perm.set_discordid === 1 ? false : true)} onChange={ async (e) => {
                            this.setState({discordid: e.target.value})
                        }} value={this.state.discordid}/>
                        {perm.set_discordid === 1 &&
                        <button type={'submit'} className={'btn'}>valider</button>
                        }
                    </form>

                    </td>
                <td className={'grade'}>
                    <form onSubmit={this.isupdate}>
                        <select value={this.state.grade} onChange={(e)=>{this.setState({grade: e.target.value})}}>
                            <optgroup label={'pas d\'accès'}>
                                <option value={1} disabled={true}>user</option>
                            </optgroup>
                            <optgroup label={'membre'}>
                                <option value={2}>Probies</option>
                                <option value={3}>Probies</option>
                                <option value={4}>Engineer</option>
                                <option value={5}>Firefighter</option>
                                <option value={6}>Senior Firefighter</option>
                                <option value={7}>Lieutenant</option>
                            </optgroup>
                            <optgroup label={'référents'}>
                                <option value={8}>Capitaine</option>
                                <option value={9}>Battalion Chief</option>
                            </optgroup>
                            <optgroup label={'direction'}>
                                <option value={10}>Assistant Chief</option>
                                <option value={11}>Chief</option>
                            </optgroup>
                            <optgroup label={'autre'}>
                                <option value={12}>staff 912</option>
                                <option value={13}>Inspecteur</option>
                                <option value={14}>Développeur</option>
                            </optgroup>
                        </select>
                        {perm.grade_modify === 1 &&
                        <button type={'submit'} className={'btn'}>valider</button>
                        }
                    </form>
                </td>
                <td className={'pilote'}>
                    {perm.set_pilot === 1 &&
                        <div className={'pilote-btn'}>
                            <input type="checkbox" checked={this.props.pilote === 1} id={"toggle"+this.props.id} onClick={async () => {
                                var req = await axios({
                                    url: '/data/users/pilote/' + this.state.id,
                                    method: 'PUT'
                                })
                                this.props.update();
                            }
                            }/>
                            <div>
                                <label htmlFor={"toggle"+this.props.id}/>
                            </div>
                        </div>
                    }
                    {perm.set_pilot === 0 &&
                        <div className={'pilote-btn'}>
                            <input type="checkbox" disabled id={"toggle"+this.props.id}/>
                            <div>
                                <label htmlFor={"toggle"+this.props.id}/>
                            </div>
                        </div>
                    }
                </td>
            </tr>
        )
    }
}
PersonnelLine.contextType = PermsContext;

export default PersonnelLine;
