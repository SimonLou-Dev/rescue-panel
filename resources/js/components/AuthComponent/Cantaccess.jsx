import React from 'react';
import {Link} from "react-router-dom";
import axios from "axios";

class Cantaccess extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            image : ''
        }
        this.updateUserImage  = this.updateUserImage.bind(this)
        this.updateUserImage()
    }



    async updateUserImage() {
        await axios({
            method: 'GET',
            url: '/data/bg'
        }).then(r => {
            this.setState({image: r.data.image})
        })
    }

    render() {
        return (
            <div className={'Auth'} style={{backgroundImage: 'url('+this.state+')'}}>
                <div className={'Authentifier'}>
                    <section className={'auth-header'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </section>
                    <div className={'auth-content'}>
                        <h1>Vous ne pouvez pas acceder</h1>
                        <h3>Contactez un responsable</h3>
                        <h4>salon #note-mdt</h4>
                    </div>
                    <div className={'auth-footer'}>
                        <a href={'/logout'} className={'btn --medium'}>déconnexion</a>
                    </div>
                </div>
            </div>
        )
    };
}

export default Cantaccess;
