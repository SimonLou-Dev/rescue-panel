import React from 'react';
import Searcher from "../../props/Searcher";
import CardComponent from "../../props/CardComponent";

function StockSettings(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'StockSettings'}>
        <button className={'btn retour'} onClick={()=>{Redirection('/LSCoFD/logistique/stock/view')}}>
            retour
        </button>
        <CardComponent title={'item'}>
            <div className={'table-header'}>
                <Searcher/>
            </div>
            <div className={'table-content'}>
                <table>
                    <tbody>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                        <tr>
                            <td>Lance à incendie</td>
                            <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div className={'table-footer'}>
                <div className={'form-part form-line'}>
                    <input type={"text"}/>
                    <button className={'btn'}>ajouter</button>
                </div>
            </div>
        </CardComponent>
        <CardComponent title={'dépots'}>
            <div className={'table-header'}>
                <Searcher/>
            </div>
            <div className={'table-content'}>
                <table>
                    <tbody>
                    <tr>
                        <td>Paleto</td>
                        <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                    </tr>
                    <tr>
                        <td>Sandy</td>
                        <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                    </tr>
                    <tr>
                        <td>j'ai oublié le nom</td>
                        <td><img alt={''} src={'/assets/images/decline.png'}/> </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div className={'table-footer'}>
                <div className={'form-part form-line'}>
                    <input type={"text"}/>
                    <button className={'btn'}>ajouter</button>
                </div>
            </div>
        </CardComponent>
    </div> )
}

export default StockSettings;
