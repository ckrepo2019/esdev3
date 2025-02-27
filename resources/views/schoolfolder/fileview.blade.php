
																												@if($fileinfo->extension == 'png' || $fileinfo->extension == 'jpg')
																													<img  src="{{asset($fileinfo->filepath)}}" style="width: 80%;" draggable="false" style="pointer-events: none"/>
																												@elseif($fileinfo->extension == 'mp4' || $fileinfo->extension == 'mkv')
																													<video style="width: 100%;"  height="400" controls draggable="false"  style="">
																															<source src="{{asset($fileinfo->filepath)}}" type="video/mp4">
																													</video>
																												@elseif($fileinfo->extension == 'pdf')
																													<div style="width: 100%;height: 700px;position: relative;">
																														<div style=" position: absolute;
																														top: 0;
																														left: 0;
																														width: 98%;
																														height: 700px;"></div>
																														<iframe id="iframe-{{$fileinfo->id}}" src="{{asset($fileinfo->filepath)}}#toolbar=0" style="width: 100%;height: 700px;"  ></iframe>
																													</div>
																												@endif