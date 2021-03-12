import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";


class LivretFormation extends React.Component {
    render() {
        return (<div className="livret-page">
                <PagesTitle title={'Mon livret de formation'}/>
            <div className={'livret'}>
                <div className="livret-content">
                    <section className={'left'}>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                    </section>
                    <section className={'right'}>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                    </section>
                </div>
                <div className="livret-footer">
                    <button className={'btn'}>Page précédente</button>
                    <button className={'btn'}>Page suivante</button>
                </div>
            </div>
        </div>);
    }
}

class ResponsePage extends React.Component {
    render() {
        return (
            <div className="responsepage">
                <PagesTitle title={"formation | BC - Air medical support"}/>
                <div className="responsecontent">
                    <form>
                        <section className="question">
                            <div className={'left'}>
                                <h2><span>Question n°1 :</span> Comment faire exploser un hélicoptère</h2>
                                <div className={"response"}>
                                    <div className={'rowed'}>
                                        <div className={'checkbox'}>
                                            <label className="container">Lui faire des bisous
                                                <input type="checkbox" className={'user'}/>
                                                <span className="checkmark" />
                                            </label>
                                        </div>
                                    </div>
                                    <div className={'rowed'}>
                                        <div className={'checkbox'}>
                                            <label className="container">Lui faire des câlins
                                                <input checked type="checkbox" className={'user'}/>
                                                <span className="checkmark" />
                                            </label>
                                        </div>
                                    </div>
                                    <div className={'rowed'}>
                                        <div className={'checkbox'}>
                                            <label className="container disabled">Lui tirer dessus avec un lance pierre
                                                <input disabled checked type="checkbox" className={'false'}/>
                                                <span className="checkmark" />
                                            </label>
                                        </div>
                                    </div>
                                    <div className={'rowed'}>
                                        <div className={'checkbox'}>
                                            <label className="container disabled">Lui lancer des peluches
                                                <input disabled checked type="checkbox" className={'right'}/>
                                                <span className="checkmark" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="infos">
                                <img alt={""} src={"https://cdnfr1.img.sputniknews.com/img/103504/72/1035047222_0:87:2600:1493_1000x541_80_0_0_6d9fc2f49efd07d2affa215b788e494b.jpg"}/>
                                <p>Castus gabaliums ducunt ad nixus. Raptus racana satis pugnas fermium est. Cedriums cadunt in raptus vierium! Tatas tolerare in culina! Verpas credere! Cum fraticinida ridetis, omnes animalises tractare festus, brevis eposes.</p>
                            </div>
                        </section>
                        <section className="bottom">
                            <button className={'btn'}>précédent</button>
                            <h3>1 mins 27</h3>
                            <h3>choix multiple</h3>
                            <button className={'btn'} type={'submit'}>valider</button>
                        </section>
                    </form>
                </div>
            </div>

        );
    }
}

class FormationsController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: 1,
        }
    }

    render() {
        switch (this.state.status){
            case null:
                return (<LivretFormation/>)
            case 1:
                return (<ResponsePage/>)
        }
    }
}



export default FormationsController;
