import React from 'react';
import axios from "axios";
import PermsContext from "../../../context/PermsContext";

class PersonnelLine extends React.Component {
    constructor(props) {
        super(props);
        this.state = {id: this.props.id, name: this.props.name, grade: this.props.grade}
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
                <td className={'name'}>{this.state.name}</td>
                <td className={'tel'}>0000-0000-0000</td>
                <td className={'compte'}>000000000</td>
                <td className={'grade'}>
                    <form onSubmit={this.isupdate}>
                        <select value={this.state.grade} onChange={(e)=>{this.setState({grade: e.target.value})}}>
                            <optgroup label={'pas d\'accès'}>
                                <option value={0}>user</option>
                            </optgroup>
                            <optgroup label={'membre'}>
                                <option value={1}>Resident</option>
                                <option value={2}>Caregiver</option>
                                <option value={3}>Nurse</option>
                                <option value={4}>Doctor</option>
                            </optgroup>
                            <optgroup label={'référents'}>
                                <option value={5}>Senior Doctor</option>
                                <option value={6}>Team Manager</option>
                            </optgroup>
                            <optgroup label={'direction'}>
                                <option value={7}>Assistant - Chief </option>
                                <option value={8}>Paramedical - Chief</option>
                            </optgroup>
                            <optgroup label={'autre'}>
                                <option value={9}>Inspecteur</option>
                                <option value={10}>Développeur</option>
                            </optgroup>
                        </select>
                        {perm.edit_perm === 1 &&
                            <button type={'submit'} className={'btn'}>valider</button>
                        }
                    </form>
                </td>
                <td className={'pilote'}>
                    {perm.set_pilot === 1 &&
                        <div className={'pilote-btn'}>
                            <input type="checkbox" id={"toggle"+this.props.id}/>
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
