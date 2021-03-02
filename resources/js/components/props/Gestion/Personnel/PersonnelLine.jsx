import React from 'react';
import axios from "axios";

class PersonnelLine extends React.Component {
    constructor(props) {
        super(props);
        this.state = {id: this.props.id, name: this.props.name, grade: this.props.grade}
        this.isupdate = this.isupdate.bind(this);
    }

    async isupdate(e) {
        e.preventDefault();
        var req = await axios({
            url: '/data/users/setgrade/' + this.state.grade + '/' + this.state.id,
            method: 'POST',
        })
        this.props.update();

    }

    render() {
        return (
            <tr>
                <td className={'id'}>{this.state.id}</td>
                <td className={'name'}>{this.state.name}</td>
                <td className={'grade'}>
                    <form onSubmit={this.isupdate}>
                        <select value={this.state.grade} onChange={(e)=>{this.setState({grade: e.target.value})}}>
                            <option value={0}>user</option>
                            <option value={1}>Resident</option>
                            <option value={2}>Caregiver</option>
                            <option value={3}>Nurse</option>
                            <option value={4}>Doctor</option>
                            <option value={5}>Senior Doctor</option>
                            <option value={6}>Team Manager</option>
                            <option value={7}>Assistant - Chief </option>
                            <option value={8}>Paramedical - Chief</option>
                            <option value={9}>Paramedical - Chief</option>
                            <option value={10}>Inspecteur</option>
                            <option value={11}>Développeur</option>
                        </select>
                        <button type={'submit'} className={'btn'}>valider</button>
                    </form>
                </td>
            </tr>
        )
    };
}

export default PersonnelLine;
