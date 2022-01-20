import React from 'react';
import CardComponent from "../../../props/CardComponent";
import PageNavigator from "../../../props/PageNavigator";
import Searcher from "../../../props/Searcher";

function GlobalView(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'BC-GlobalView'}>
        <section className={'new'}>
            <CardComponent title={'en cours'}>
                <div className={'header'}>
                    <button className={'btn'}>ajouter</button>
                </div>
                <div className={'BCtable'}>
                    <div className={'table-item'}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Fusillade - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Explosion - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                </div>
            </CardComponent>
        </section>
        <section className={'alt'}>
            <CardComponent title={'terminé(s)'}>
                <div className={'header'}>
                    <Searcher/>
                    <button className={'btn'}><img alt={''} src={'/assets/images/xls.png'}/></button>
                    <PageNavigator/>
                </div>
                <div className={'BCtable'}>
                    <div className={'table-item'} onClick={()=>{Redirection('/blackcodes/fire/1')}}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'} onClick={()=>{Redirection('/blackcodes/medic/1')}}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Fusillade - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Explosion - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Fusillade - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/OMC.png'} alt={''}/><h5>Explosion - Paleto Bay</h5>
                    </div>
                    <div className={'table-item'}>
                        <img src={'/assets/images/LSCoFD.png'} alt={''}/><h5>Feu - Paleto Bay</h5>
                    </div>
                </div>
            </CardComponent>

        </section>
    </div> )
}

export default GlobalView;
