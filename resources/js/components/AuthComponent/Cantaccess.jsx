import React from 'react';
import {Link} from "react-router-dom";

class Cantaccess extends React.Component {
    render() {
        return (
            <div className={'maintenance'}>
                <div className={'card'}>
                    <section className={'image'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </section>
                    <h1>Vous ne pouvez pas acceder</h1>

                    <a className={'btn'} href={'/logout'}>Se deconnecter</a>

                    <section className={'contact'}>
                        <h3>Contactez un responsable</h3>
                        <h4>salon #note-mdt</h4>
                    </section>
                </div>
            </div>
        )
    };
}

export default Cantaccess;
