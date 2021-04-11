import React, {useState,useRef, useCallback, useContext} from 'react';
import { v4 } from 'uuid';
import ChunkedUploady, {
    UploadyContext,
    useItemProgressListener, useFileInput,
    useChunkStartListener,

} from "@rpldy/chunked-uploady";
import {useBatchFinishListener} from "@rpldy/shared-ui";
import axios from "axios";

const CustomButton = (props) => {
    const uploady = useContext(UploadyContext);
    const hanldeUpload = useCallback(()=> {
        uploady.showFileUpload();
    },[uploady]);
    return <button onClick={hanldeUpload} className={'upload-btn ' + (props.hasFile ===true ? 'hasFile' : '')} disabled={props.disabled}>Ajouter une image</button>
}

export default function Uploader(props){
    const [file, setFile] = useState(false);
    const [FileId, setFileId] = useState(v4());
    const [disabled, setDisabled] = useState(false);
    const [imgUrl, setImgUrl] = useState(null);

    const UploadFinished = async (type, id) => {
        console.log('call')
        let req = await axios({
            method: 'PUT',
            url: '/data/finish/tempupload/' + id,
            data: {
                type: type
            }
        })

        if(req.status === 200){
            setImgUrl(req.data.image);
            if(props && props.images){
                props.images(req.data.image);
            }
        }
        setDisabled(false);
    }

    const FileAdded= (e) => {
        setFile(true);
        setDisabled(true)
        setFileId(v4);
    }

    const deleteFile = async () => {
        let clear = await axios({
            url: '/data/delete/tempupload',
            method: 'DELETE',
            data: {
                image: imgUrl,
            }
        })
        if(props && props.image){
            props.images(null)
        }
        setFile(false);
        setDisabled(false);
        setImgUrl(null);
        setFileId(v4);
    }

    return (
        <div className={'uploader--container'}>
            <ChunkedUploady
                showFileUpload={true}
                chunkSize={100000}
                sendWithFormData={true}
                retries={2}
                debug={false}
                chunked={true}
                params={{id:FileId}}
                customInput={true}
                inputFieldName={'file'}
                destination={{ url: "/data/tempupload", headers: {'X-CSRF-TOKEN':csrf} }}
            >
                <div className={'Uploader'}>
                    <img className={'img'} src={'/storage/temp_upload/'+imgUrl} alt={''}/>
                    <label className={(file ===true ? 'hasFile' : '')}>{props.text ? props.text : '1920*1080 2MO'}</label>
                    <button disabled={disabled} className={'delete ' + (file ===true ? '' : 'anyFile')} onClick={deleteFile}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    <CustomButton disabled={disabled} hasFile={file}/>
                    <CustomFrom disabled={disabled} fileadded={FileAdded}/>
                    <UploadProgress uploadfinished={UploadFinished}/>
                </div>
            </ChunkedUploady>
        </div>

    )

}

const UploadProgress = (props) => {
    const [progress, setProgess] = useState(0);
    const [first,setFirst] = useState(0)
    const [inRun, setRun] =useState(false);
    const [imgId, setimgId] = useState(null);

    const progressData = useItemProgressListener();

    useChunkStartListener((data) => {
        if(data.chunkCount){
            if(!inRun){
                setFirst(data.chunkCount)
                setRun(true)
            }else{
                setProgess(Math.abs((data.chunkCount/first)*100-100));
                if(!imgId){
                    setimgId(data.sendOptions.params.id);
                }
            }
        }
    });

    useBatchFinishListener((batch) => {
        setRun(false)
        setProgess(100)
        console.log('finished')
        console.log(batch)
        props.uploadfinished(batch.items[0].file.type, imgId);
        setimgId(null)
    });

    return (
        progressData && (
            <div className={"bar"}>
                <div style={{width: progress + '%'}} className={'bar--filler'}/>
            </div>
        )
    );
};

const CustomFrom = (props) => {
    const inputRef = useRef();
    useFileInput(inputRef);

    return (
        <input type="file" name="testFile" style={{ display: "none" }} ref={inputRef} disabled={props.disabled} onChange={props.fileadded}/>
    )
}
