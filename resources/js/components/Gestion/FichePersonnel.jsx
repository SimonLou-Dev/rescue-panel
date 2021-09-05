import React from 'react';
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";
import {rootUrl} from "../props/Gestion/Content/ContentCard";

class FichePersonnel extends React.Component {
    render() {
        return (
            <div className={'FichePersonnel'}>
                <section className={'header'}>
                    <Link to={'/gestion/personnel'} className={'btn'}>retour</Link>
                    <a className={'btn'}>exporter</a>
                    <PagesTitle title={'Fiche Personnel'}/>
                    <button className={'btn'}>déclarer la démission</button>
                </section>
                <section className={'content'}>
                    <div className={'infos'}>
                        <div className={'infoCat'}>
                            <h2>Information entreprise</h2>
                            <div className={'infoList'}>
                                <h4><span>Personnel : </span> Simon lou</h4>
                                <h4><span>Inscrit le : </span> 05/05/05</h4>
                                <h4><span>Grade actuel : </span> Lead Firefighter</h4>
                                <h4><span>Matricule : </span> 81</h4>
                            </div>
                        </div>
                        <div className={'infoCat'}>
                            <h2>Information personnelles</h2>
                            <div className={'infoList'}>
                                <h4><span>Lieux de résidence : </span> LS</h4>
                                <h4><span>N° de téléphone : </span> 555555</h4>
                                <h4><span>N° de compte : </span> 5555555555</h4>
                                <h4><span>Discord id : </span> 883757015606906931</h4>
                            </div>
                        </div>


                    </div>

                    <div className={'sanctions'}>
                        <div className={'heading'}>
                            <h1>Liste des sanctions</h1>
                            <button className={'btn'}>Ajouter</button>
                        </div>
                        <ul className={'sanctionsListe'}>
                            <li>
                                <button className={'btn deleter'}><img src={rootUrl + 'assets/images/cancel.png'}/></button>
                                <h4><span>Type : </span> mise à pied</h4>
                                <h4><span>Prononcé le : </span> 13/06/2001 à 16h30 </h4>
                                <h4><span>Fin : </span> 17/06/2001 16h30 </h4>
                                <h4><span>Prononcé par : </span> 4J </h4>
                                <h4><span>Raison : </span> Ce matériel ne peut être utilisé à des fins personnelles sans autorisation du supérieur hiérarchique. </h4>
                            </li>
                            <li>
                                <button className={'btn deleter'}><img src={rootUrl + 'assets/images/cancel.png'}/></button>
                                <h4><span>Type : </span> mise à pied</h4>
                                <h4><span>Prononcé le : </span> 13/06/2001 à 16h30 </h4>
                                <h4><span>Fin : </span> 17/06/2001 16h30 </h4>
                                <h4><span>Prononcé par : </span> 4J </h4>
                                <h4><span>Raison : </span> Ce matériel ne peut être utilisé à des fins personnelles sans autorisation du supérieur hiérarchique. </h4>
                            </li>
                            <li>
                                <button className={'btn deleter'}><img src={rootUrl + 'assets/images/cancel.png'}/></button>
                                <h4><span>Type : </span> mise à pied</h4>
                                <h4><span>Prononcé le : </span> 13/06/2001 à 16h30 </h4>
                                <h4><span>Fin : </span> 17/06/2001 16h30 </h4>
                                <h4><span>Prononcé par : </span> 4J </h4>
                                <h4><span>Raison : </span> Ce matériel ne peut être utilisé à des fins personnelles sans autorisation du supérieur hiérarchique. </h4>
                            </li>
                        </ul>
                    </div>
                    <div className={'notes'}>
                        <h1>Notes</h1>
                        <ul className={'notelist'}>
                            <li className={'note'}>
                                <button className={'btn deleter'}><img src={rootUrl + 'assets/images/cancel.png'}/></button>
                                <h4><span>Ecrit par :</span> jean claude</h4>
                                <h4><span>Date :</span> 15/05/2012</h4>
                                <h4>Gratis mortem rare falleres navis est. A falsis, lamia barbatus hydra.</h4>
                            </li>
                        </ul>
                        <div className={'noteadder'}>
                            <form>
                                <textarea>Ecrire une note...</textarea>
                                <button type={'submit'} className={'btn'}>valider</button>
                            </form>
                        </div>
                    </div>
                    <div className={'formations'}>
                        <h1>test</h1>
                    </div>
                </section>
            </div>
        )
    };
}

export default FichePersonnel;
