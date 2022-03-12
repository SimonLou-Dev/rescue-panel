import React, {useContext, useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import CardComponent from "../../props/CardComponent";
import SwitchBtn from "../../props/SwitchBtn";
import axios from "axios";
import UserContext from "../../context/UserContext";

function grade(props) {
    const [gradeList, setGradeList] = useState([]);
    const [gradeSelected, selectGrade] = useState(null);
    const me = useContext(UserContext);

    useEffect(()=>{
        LoadPage();
    }, [])

    const LoadPage = async ()=> {
        await axios({
            method: 'GET',
            url: '/data/admin/grades',
        }).then(r => {
            setGradeList(r.data.grades);
        })
    }

    const updateGrade = (item, value = null)  => {
        if(value === null){
            value = !gradeSelected[item]
        }
        selectGrade(prevState => ({
            ...prevState,
            [item]: value,
        }))
    }


    return (<div className={'GradeList'}>
        <section className={'grades-selector'}>
            <div className={'grade-list'}>
                <table>
                    <thead>
                        <tr>
                            <th className={'grade'}>Grade</th>
                            <th className={'power'}>puissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        {gradeList && gradeList.map((g)=>
                            <tr key={g.id} onClick={()=>{selectGrade(g)}}>
                                <td className={'grade'}>{g.name}</td>
                                <td className={'power'}>{g.power}</td>
                            </tr>
                        )}

                        <tr className={'adder'} onClick={async () => {
                            await axios({
                                method: 'POST',
                                url: '/data/admin/grades',
                            }).then(r => {
                                setGradeList(r.data.grades)
                            });
                        }}>
                            <td className={'grade'}>ajouter</td>
                            <td className={'power'}><img src={'/assets/images/add.png'} alt={''}/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        {gradeSelected !== null &&
            <section className={'bigpart'}>
                <div className={'bigbordel'}>
                    <div className={'header'}>
                        <input type={'text'} placeholder={'nom du grade'} value={gradeSelected.name}
                               onChange={(e)=>{updateGrade('name', e.target.value)}}/>
                        <div className={'form-part form-inline'}>
                            <label>puissance : </label>
                            <input type={'number'} value={gradeSelected.power}
                                   onChange={(e)=>{updateGrade('power', e.target.value)}}/>
                        </div>
                        <button className={'btn'} disabled={!(me.grade.admin || me.grade.modify_grade)} onClick={async () => {
                        await axios({
                            method: 'put',
                            url:'/data/admin/grades',
                            data: {
                                'grade': gradeSelected
                            }
                        }).then(r => {
                            setGradeList(r.data.grades)
                        });
                        }}><img alt={''} src={'/assets/images/save.png'}/></button>
                        <button className={'btn'}  onClick={async () => {
                            await axios({
                                method: 'DELETE',
                                url:'/data/admin/grades',
                                data:{
                                    'grade_id':gradeSelected.id,
                                }

                            }).then(r => {
                                setGradeList(r.data.grades)
                                selectGrade(null)
                            });
                        }}>supprimer</button>
                    </div>
                    <div className={'perm-editor'}>
                        <div className={'perm-line'}>
                            <h4>général</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>administrateur (pas besoin de mettre d'autres perm)</th>
                                    <th>accès</th>
                                    <th>avoir un matricule</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'1'} checked={gradeSelected.admin} callback={()=>{updateGrade('admin')}}/></td>
                                    <td><SwitchBtn number={'2'} checked={gradeSelected.access} callback={()=>{updateGrade('access')}}/></td>
                                    <td><SwitchBtn number={'3'} checked={gradeSelected.having_matricule} callback={()=>{updateGrade('having_matricule')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>Rapports</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>créer</th>
                                    <th>modifier</th>
                                    <th>voir (en service)</th>
                                    <th>voir (tout le temp)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'4'} checked={gradeSelected.rapport_create} callback={()=>{updateGrade('rapport_create')}}/></td>
                                    <td><SwitchBtn number={'7'} checked={gradeSelected.rapport_modify} callback={()=>{updateGrade('rapport_modify')}}/></td>
                                    <td><SwitchBtn number={'5'} checked={gradeSelected.rapport_view} callback={()=>{updateGrade('rapport_view')}}/></td>
                                    <td><SwitchBtn number={'6'} checked={gradeSelected.rapport_HS} callback={()=>{updateGrade('rapport_HS')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>Test de poudre</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>créer</th>
                                    <th>voir (en service)</th>
                                    <th>voir (tout le temp)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'8'} checked={gradeSelected.poudretest_create} callback={()=>{updateGrade('poudretest_create')}}/></td>
                                    <td><SwitchBtn number={'9'} checked={gradeSelected.poudretest_view} callback={()=>{updateGrade('poudretest_view')}}/></td>
                                    <td><SwitchBtn number={'10'} checked={gradeSelected.poudretest_HS} callback={()=>{updateGrade('poudretest_HS')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>Dossiers</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>modifier (infos patient)</th>
                                    <th>voir (en service)</th>
                                    <th>voir (tout le temp)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'11'} checked={gradeSelected.patient_edit} callback={()=>{updateGrade('patient_edit')}}/></td>
                                    <td><SwitchBtn number={'12'} checked={gradeSelected.dossier_view} callback={()=>{updateGrade('dossier_view')}}/></td>
                                    <td><SwitchBtn number={'13'} checked={gradeSelected.dossier_HS} callback={()=>{updateGrade('dossier_HS')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>BC & Fire</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>voir (tout le temp)</th>
                                    <th>ouvrir</th>
                                    <th>fermer</th>
                                    <th>ajouter un patient</th>
                                    <th>ajouter du personnel (feux uniquement)</th>
                                    <th>acceder aux feux</th>
                                    <th>acceder aux medic</th>
                                    <th>modifier les infos</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'14'} checked={gradeSelected.BC_HS} callback={()=>{updateGrade('BC_HS')}}/></td>
                                    <td><SwitchBtn number={'15'} checked={gradeSelected.BC_open} callback={()=>{updateGrade('BC_open')}}/></td>
                                    <td><SwitchBtn number={'16'} checked={gradeSelected.BC_close} callback={()=>{updateGrade('BC_close')}}/></td>
                                    <td><SwitchBtn number={'17'} checked={gradeSelected.BC_modify_patient} callback={()=>{updateGrade('BC_modify_patient')}}/></td>
                                    <td><SwitchBtn number={'18'} checked={gradeSelected.BC_fire_personnel_add} callback={()=>{updateGrade('BC_fire_personnel_add')}}/></td>
                                    <td><SwitchBtn number={'19'} checked={gradeSelected.BC_fire_view} callback={()=>{updateGrade('BC_fire_view')}}/></td>
                                    <td><SwitchBtn number={'20'} checked={gradeSelected.BC_medic_view} callback={()=>{updateGrade('BC_medic_view')}}/></td>
                                    <td><SwitchBtn number={'21'} checked={gradeSelected.BC_edit} callback={()=>{updateGrade('BC_BC_edit')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>factures</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>voir (en service)</th>
                                    <th>voir (tout le temp)</th>
                                    <th>créer une facture</th>
                                    <th>exporter une facture</th>
                                    <th>payer une facture</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'23'} checked={gradeSelected.facture_view} callback={()=>{updateGrade('facture_view')}}/></td>
                                    <td><SwitchBtn number={'24'} checked={gradeSelected.facture_HS} callback={()=>{updateGrade('facture_HS')}}/></td>
                                    <td><SwitchBtn number={'25'} checked={gradeSelected.facture_create} callback={()=>{updateGrade('facture_create')}}/></td>
                                    <td><SwitchBtn number={'26'} checked={gradeSelected.facture_export} callback={()=>{updateGrade('facture_export')}}/></td>
                                    <td><SwitchBtn number={'27'} checked={gradeSelected.facture_paye} callback={()=>{updateGrade('facture_paye')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>Personnel</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>voir liste du personnel</th>
                                    <th>activer le cross service</th>
                                    <th>modifier le grade</th>
                                    <th>mettre pilote</th>
                                    <th>voir la fiche personnel</th>
                                    <th>ajouter une note</th>
                                    <th>mettre une MAP</th>
                                    <th>exlure qqn</th>
                                    <th>mettre un avertissement</th>
                                    <th>modifier le matériel</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'28'} checked={gradeSelected.view_PersonnelList} callback={()=>{updateGrade('view_PersonnelList')}}/></td>
                                    <td><SwitchBtn number={'29'} checked={gradeSelected.set_crossService} callback={()=>{updateGrade('set_crossService')}}/></td>
                                    <td><SwitchBtn number={'30'} checked={gradeSelected.set_grade} callback={()=>{updateGrade('set_grade')}}/></td>
                                    <td><SwitchBtn number={'31'} checked={gradeSelected.set_pilote} callback={()=>{updateGrade('set_pilote')}}/></td>
                                    <td><SwitchBtn number={'32'} checked={gradeSelected.view_personnelSheet} callback={()=>{updateGrade('view_personnelSheet')}}/></td>
                                    <td><SwitchBtn number={'33'} checked={gradeSelected.add_note} callback={()=>{updateGrade('add_note')}}/></td>
                                    <td><SwitchBtn number={'34'} checked={gradeSelected.add_MAP_sanction} callback={()=>{updateGrade('add_MAP_sanction')}}/></td>
                                    <td><SwitchBtn number={'35'} checked={gradeSelected.add_exlude_sanction} callback={()=>{updateGrade('add_exlude_sanction')}}/></td>
                                    <td><SwitchBtn number={'36'} checked={gradeSelected.add_warn_sanction} callback={()=>{updateGrade('add_warn_sanction')}}/></td>
                                    <td><SwitchBtn number={'37'} checked={gradeSelected.modify_material} callback={()=>{updateGrade('modify_material')}}/></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div className={'perm-line'}>
                            <h4>demandes de services</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>faire une demande</th>
                                    <th>voir mes demandes</th>
                                    <th>voir toutes les demandes</th>
                                    <th>accepter / refuser une demande</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'38'} checked={gradeSelected.post_service_req} callback={()=>{updateGrade('post_service_req')}}/></td>
                                    <td><SwitchBtn number={'39'} checked={gradeSelected.view_service_req} callback={()=>{updateGrade('view_service_req')}}/></td>
                                    <td><SwitchBtn number={'40'} checked={gradeSelected.viewAll_service_req} callback={()=>{updateGrade('viewAll_service_req')}}/></td>
                                    <td><SwitchBtn number={'41'} checked={gradeSelected.modify_service_req} callback={()=>{updateGrade('modify_service_req')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>demandes de primes</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>faire une demande</th>
                                    <th>voir mes demandes</th>
                                    <th>voir toutes les demandes</th>
                                    <th>accepter / refuser une demande</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'42'} checked={gradeSelected.post_prime_req} callback={()=>{updateGrade('post_prime_req')}}/></td>
                                    <td><SwitchBtn number={'43'} checked={gradeSelected.view_prime_req} callback={()=>{updateGrade('view_prime_req')}}/></td>
                                    <td><SwitchBtn number={'44'} checked={gradeSelected.viewAll_prime_req} callback={()=>{updateGrade('viewAll_prime_req')}}/></td>
                                    <td><SwitchBtn number={'45'} checked={gradeSelected.modify_prime_req} callback={()=>{updateGrade('modify_prime_req')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>demandes d'absences</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>faire une demande</th>
                                    <th>voir mes demandes</th>
                                    <th>voir toutes les demandes</th>
                                    <th>accepter / refuser une demande</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'46'} checked={gradeSelected.post_absences_req} callback={()=>{updateGrade('post_absences_req')}}/></td>
                                    <td><SwitchBtn number={'47'} checked={gradeSelected.view_absences_req} callback={()=>{updateGrade('view_absences_req')}}/></td>
                                    <td><SwitchBtn number={'48'} checked={gradeSelected.viewAll_absences_req} callback={()=>{updateGrade('viewAll_absences_req')}}/></td>
                                    <td><SwitchBtn number={'49'} checked={gradeSelected.modify_absences_req} callback={()=>{updateGrade('modify_absences_req')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>service</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>voir le rapport horaire</th>
                                    <th>me mettre en service</th>
                                    <th>mettre qqn en service</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'50'} checked={gradeSelected.view_rappportHoraire} callback={()=>{updateGrade('view_rappportHoraire')}}/></td>
                                    <td><SwitchBtn number={'51'} checked={gradeSelected.set_service} callback={()=>{updateGrade('set_service')}}/></td>
                                    <td><SwitchBtn number={'52'} checked={gradeSelected.set_other_service} callback={()=>{updateGrade('set_other_service')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className={'perm-line'}>
                            <h4>grade</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th>voir la liste des grades</th>
                                    <th>modifier un grade</th>
                                    <th>editer dans la gestion de contenue</th>
                                    <th>edit les chann discord</th>
                                    <th>voir les logs</th>
                                    <th>poster une annonce</th>
                                    <th>poster une actualitée</th>
                                    <th>editer les infos utiles</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><SwitchBtn number={'53'} checked={gradeSelected.view_grade_list} callback={()=>{updateGrade('view_grade_list')}}/></td>
                                    <td><SwitchBtn number={'54'} checked={gradeSelected.modify_grade} callback={()=>{updateGrade('modify_grade')}}/></td>
                                    <td><SwitchBtn number={'55'} checked={gradeSelected.modify_gestionContent} callback={()=>{updateGrade('modify_gestionContent')}}/></td>
                                    <td><SwitchBtn number={'56'} checked={gradeSelected.modify_discordChann} callback={()=>{updateGrade('modify_discordChann')}}/></td>
                                    <td><SwitchBtn number={'57'} checked={gradeSelected.view_logs} callback={()=>{updateGrade('view_logs')}}/></td>
                                    <td><SwitchBtn number={'58'} checked={gradeSelected.post_annonces} callback={()=>{updateGrade('post_annonces')}}/></td>
                                    <td><SwitchBtn number={'59'} checked={gradeSelected.post_actualities} callback={()=>{updateGrade('post_actualities')}}/></td>
                                    <td><SwitchBtn number={'60'} checked={gradeSelected.edit_infos_utils} callback={()=>{updateGrade('edit_infos_utils')}}/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </section>
        }






    </div> )
}

export default grade;
