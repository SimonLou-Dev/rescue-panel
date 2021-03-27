import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";
import {Button} from "bootstrap/js/src";

class FormaUserList extends React.Component {
    render() {
        return (
            <div className="f-userlist">
                <section className="header">
                    <PagesTitle title={'Certifications des utilisateurs'}/>
                    <button onClick={()=>this.props.change(1)} className={'btn'}>Liste des formations</button>
                </section>
                <section className="user-list">
                    <table>
                        <thead>
                            <tr>
                                <th className={'name'}>nom</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td className={'name'}>Simon Lou</td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle_"+this.props.id }/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        );
    }
}

class FormaList extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="f-formalist">
                <section className="header">
                    <PagesTitle title={'Liste des formations'}/>
                    <button onClick={()=>this.props.change(0)} className={'btn'}>Certifications</button>
                </section>
            </div>
        );
    }
}

class FormaCreate extends React.Component {
    render() {
        return null;
    }
}

class AFormaController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: 2,
        }
    }


    render() {
        switch (this.state.status){
            case 0:
                return (<FormaUserList change={(page)=>this.setState({status: page})}/>)
            case 1:
                return (<FormaList change={(page)=>this.setState({status: page})}/>)
            case 2:
                return (<FormaCreate change={(page)=>this.setState({status: page})}/>)
        }
    }
}

export default AFormaController;
