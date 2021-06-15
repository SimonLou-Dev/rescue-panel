import React, {useState, useEffect} from 'react';
import ReactQuill from "react-quill";
import 'react-quill/dist/quill.snow.css';
import PagesTitle from "../props/utils/PagesTitle";
import axios from "axios";



const LiensUtilesMgt = (props) => {
    const [text, settext] = useState('');
    const b = 0;

    useEffect(async ()=> {
        await axios({
            method: 'GET',
            url: '/data/infosutils/get',
        }).then(response => {
            settext(response.data.infos);
        });
    }, [b])

    const postdata = async () => {
        await axios({
            method: 'PUT',
            url: '/data/infosutils/put',
            data: {
                text: text
            }
        })
    }

    return (
        <div className={'GestionsUtils'}>
            <section className={'head'}>
                <button className={'btn'} onClick={postdata}>enregistrer</button>
                <PagesTitle title={'Liens utiles'}/>
            </section>
            <section className={'content'}>
                <div className={'preview'}>
                    <h1 className={'utilsName'}>Liens utiles</h1>
                    <div className={'render'} id={'UtilsRendering'} dangerouslySetInnerHTML={{__html:text}}/>
                </div>
                <div className={'writing'}>
                    <ReactQuill value={text} onChange={(value)=>settext(value)}/>
                </div>
            </section>



        </div>
    )
}



export default LiensUtilesMgt;
