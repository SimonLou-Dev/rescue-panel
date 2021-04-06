import React from 'react';

class Mdpreset extends React.Component {
    render() {
        return (
            <div className={'maintenance'}>
                <div className={'card'}>
                    <section className={'image'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </section>
                    <h1>maintenance en cours</h1>
                    <section className={'infos'}>
                        <div className="rowed">
                            <h3>Début de la maintenance : </h3>
                            <h3>00/00/0000 à 00h00 [FR]</h3>
                        </div>
                        <div className="rowed">
                            <h3>Durée prévue : </h3>
                            <h3>02h00</h3>
                        </div>
                        <div className="rowed">
                            <h3>raison : </h3>
                            <h3>mise à jour</h3>
                        </div>
                        <div className="rowed">
                            <h3>dernière  vérification : </h3>
                            <h3>00h00 [FR]</h3>
                        </div>
                    </section>
                    <section className={'contact'}>
                        <h3>Plus d'information sur discord</h3>
                        <h4>salon #note-mdt</h4>
                    </section>
                </div>
            </div>
        )
    };
}

export default Mdpreset;
